<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Traits\NotificationFirebaseTrait;
use App\Http\Traits\Upload_Files;
use App\Models\Admin;
use App\Models\User;
use App\Models\Team;
use App\Models\UserTalant;
use App\Models\Product;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\TeamPlayer;
use App\Models\Award;
use App\Models\AwardRedeemRequest;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use Upload_Files;

    public function index()
    {
        // Get total counts
        $data['totalUsers'] = User::count();
        $data['totalTeams'] = Team::count();
        $data['totalTalents'] = UserTalant::where('status',1)->count();
        $data['totalProducts'] = Product::count();
        $data['totalPlayers'] = TeamPlayer::count();
        $data['totalAwards'] = Award::count();
        $data['totalRedeemRequests'] = AwardRedeemRequest::count();

        // Get top users by points
        $data['topUsers'] = User::orderBy('points', 'desc')
            ->take(5)
            ->get();

        // Get recent award redeem requests
        $data['recentRedeemRequests'] = AwardRedeemRequest::with(['user', 'award'])
            ->latest()
            ->take(5)
            ->get();

        // Get award distribution by status
        $awardDistribution = AwardRedeemRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucfirst($item->status),
                    'count' => $item->count
                ];
            });

        $data['awardDistributionLabels'] = $awardDistribution->pluck('label');
        $data['awardDistributionData'] = $awardDistribution->pluck('count');

        // Get user growth data for the last 6 months
        $userGrowthData = [];
        $userGrowthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $userGrowthLabels[] = $date->format('M Y');
            $userGrowthData[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        $data['userGrowthLabels'] = $userGrowthLabels;
        $data['userGrowthData'] = $userGrowthData;

        // Get talent distribution by status
        $talentDistribution = UserTalant::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                $statusLabels = [
                    '1' => 'Pending',
                    '2' => 'Approved',
                    '3' => 'Rejected',
                    '4' => 'Hidden'
                ];
                return [
                    'label' => $statusLabels[$item->status] ?? 'Unknown',
                    'count' => $item->count
                ];
            });

        $data['talentDistributionLabels'] = $talentDistribution->pluck('label');
        $data['talentDistributionData'] = $talentDistribution->pluck('count');

        // Get recent activities
        $data['recentActivities'] = Notification::latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return (object)[
                    'title' => $notification->model_name,
                    'description' => $notification->message,
                    'type' => $this->getNotificationType($notification->model_name),
                    'icon' => $this->getActivityIcon($this->getNotificationType($notification->model_name)),
                    'created_at' => $notification->created_at
                ];
            });

        // Get top teams
        $data['topTeams'] = Team::withCount('userTeams')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent players
        $data['recentPlayers'] = TeamPlayer::with('team')
            ->latest()
            ->take(5)
            ->get();

        // Get players distribution by team
        $playersDistribution = TeamPlayer::with('team')
            ->selectRaw('team_id, count(*) as count')
            ->groupBy('team_id')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->team->title ?? 'Unknown Team',
                    'count' => $item->count
                ];
            });

        $data['playersDistributionLabels'] = $playersDistribution->pluck('label');
        $data['playersDistributionData'] = $playersDistribution->pluck('count');

        // Get favorite teams distribution
        $favoriteTeamsDistribution = DB::table('user_favourite_team_and_players')
            ->join('teams', 'user_favourite_team_and_players.foriegn_key', '=', 'teams.id')
            ->where('user_favourite_team_and_players.id_belong_to', 1)
            ->selectRaw('teams.title, count(*) as count')
            ->groupBy('teams.id', 'teams.title')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $title = json_decode($item->title, true);
                return [
                    'title' => $title[app()->getLocale()] ?? $title['en'] ?? 'Unknown Team',
                    'count' => $item->count
                ];
            });

        $data['favoriteTeamsDistributionLabels'] = $favoriteTeamsDistribution->pluck('title');
        $data['favoriteTeamsDistributionData'] = $favoriteTeamsDistribution->pluck('count');

        $data['totalTalents'] = UserTalant::count();
        return view('Admin.home.index', $data);
    }

    private function getActivityIcon($type)
    {
        $icons = [
            'user' => 'account',
            'team' => 'account-group',
            'talent' => 'star',
            'product' => 'shopping',
            'default' => 'bell'
        ];

        return $icons[$type] ?? $icons['default'];
    }

    private function getNotificationType($modelName)
    {
        $types = [
            'User' => 'user',
            'Team' => 'team',
            'UserTalant' => 'talent',
            'Product' => 'product',
            'default' => 'info'
        ];

        return $types[$modelName] ?? $types['default'];
    }

    public function calender(Request $request)
    {
        $arrResult =[];
        $orders = Booking::get();
        //get count of orders by days
        foreach ($orders as $row) {
            $date = date('Y-m-d', strtotime($row->created_at));
            if (isset($arrResult[$date])) {
                $arrResult[$date]["counter"] += 1;
                $arrResult[$date]["id"][]  = $row->id;
            } else {
                $arrResult[$date]["counter"] = 1;
                $arrResult[$date]["id"][]  = $row->id;

            }
        }
        //  dd($arrResult);
        //make format of calender
        $Events = [];
        if (count($arrResult)>0) {
            $i = 0;
            foreach ($arrResult as $item => $value) {
                $title= $value['counter'];
                $Events[$i] = array(
                    'id' => $i,
                    'title' => $title,
                    'start' => $item,
                    'ids' => $value['id'],
                );
                $i++;
            }
        }
        //return to calender
        return $Events ;
    }//end fun






    public function requests_calenders(){
      return view('Admin.requests.calenders.index');
    }




}//end clas
