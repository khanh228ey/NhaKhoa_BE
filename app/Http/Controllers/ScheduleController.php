<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use App\RequestValidations\ScheduleValidation;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    //
    protected $ScheduleRepository;
    protected $ScheduleValidation;
    public function __construct(ScheduleValidation $ScheduleValidation, ScheduleRepository $ScheduleRepository)
    {
        $this->ScheduleValidation = $ScheduleValidation;
        $this->ScheduleRepository = $ScheduleRepository; 
      
    }
    Public function createSchedule(Request $request){
        // $validator = $this->ScheduleValidation->Schedule();
        // if ($validator->fails()) {
        //       return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        // }
        $history = $this->ScheduleRepository->addSchedule($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(200, ConstantsMessage::Add, $history, 200);
    }

    public function getSchedule(Request $request)
            {
                $doctor_id = $request->get('doctor_id');
                $date = $request->get('date');
                $perPage = $request->get('limit', 5); // Số lượng ngày trên mỗi trang, mặc định là 5
                $page = $request->get('page'); // Trang hiện tại

                $query = Schedule::query()->select('date')->distinct()
                            ->orderBy('date', 'ASC');

                if ($doctor_id) {
                    $query->where('doctor_id', $doctor_id);
                }
                if ($date) {
                    $query->whereDate('date', $date);
                }

                // Nếu không có trang được chỉ định, lấy toàn bộ dữ liệu
                if (is_null($page)) {
                    $distinctDates = $query->pluck('date')->toArray();
                    $schedules = Schedule::with(['doctor', 'time'])
                                ->whereIn('date', $distinctDates)
                                ->orderBy('date', 'ASC')
                                ->orderBy('doctor_id', 'ASC')
                                ->orderBy('created_at', 'DESC')
                                ->get();
                } else {
                    // Lấy danh sách các ngày khác nhau
                    $distinctDates = $query->pluck('date')->toArray();

                    // Phân trang ngày
                    $startIndex = ($page - 1) * $perPage;
                    $paginatedDates = array_slice($distinctDates, $startIndex, $perPage);

                    // Lọc các lịch trình theo các ngày được phân trang
                    $schedules = Schedule::with(['doctor', 'time'])
                                ->whereIn('date', $paginatedDates)
                                ->orderBy('date', 'ASC')
                                ->orderBy('doctor_id', 'ASC')
                                ->orderBy('created_at', 'DESC')
                                ->get();
                }

                // Tạo một mảng kết quả để gom lại theo ngày và id bác sĩ
                $result = [];
                foreach ($schedules as $schedule) {
                    $key = $schedule->date . '_' . $schedule->doctor_id;

                    if (!isset($result[$key])) {
                        $result[$key] = [
                            'doctor' => [
                                'id' => $schedule->doctor_id,
                                'name' => $schedule->doctor->name,
                            ],
                            'date' => $schedule->date,
                            'times' => [],
                        ];
                    }
                    // Thêm khung giờ vào mảng times của key tương ứng
                    $result[$key]['times'][] = $schedule->time->time;
                }
                // Chuyển đổi kết quả từ dạng liên kết sang mảng chỉ định
                $result = array_values($result);

                return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
            }

    
    

    
}
