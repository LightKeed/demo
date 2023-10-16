@php
    use App\Models\DepartmentPosition;

    $result = [];
    $type = $data->type;
    $ids = $data->ids;

    foreach($ids as $id) {
        $position = DepartmentPosition::where('id', '=', $id)
            ->with('department', 'employee', 'address')
            ->first();

        if($position) {
            $phone = $position->employee->phone;
            $email = $position->employee->email;

            if($type == 1) {
                $result[] = [
                    'photo_id' => $position->employee->photo_id,
                    'title' => $position->department ? $position->department->title : '',
                    'subtitle' => "{$position->employee->surname} {$position->employee->name} {$position->employee->patronymic}<br>{$position->title}",
//                    'address' => $position->address ? $position->address->fullTitle : ($position->department->address ? $position->department->address->fullTitle : null),
                    'phone' => $phone,
                    'email' => $email
                ];
            } else if($type == 2) {
                $result[] = [
                    'photo_id' => $position->employee->photo_id,
                    'title' => $position->title,
                    'subtitle' => "{$position->employee->surname} {$position->employee->name} {$position->employee->patronymic}",
                    'address' => $position->address ? $position->address->fullTitleCard : ($position->department->address ? $position->department->address->fullTitleCard : null),
                    'phone' => $phone,
                    'email' => $email
                ];
            } else if($type == 3) {
                $themes = $position->employee->attributes()->filter(['title' => 'themes'])->first()->value ?? [];

                $result[] = [
                    'photo_id' => $position->employee->photo_id,
                    'title' => "{$position->employee->surname} {$position->employee->name} {$position->employee->patronymic}",
                    'subtitle' => "{$position->title}<br>{$position->department->title}",
                    'description' => $position->employee->level_education,
                    'address' => $position->address ? "{$position->address->fullTitleCard} $position->cabinet" : ($position->department->address ? "{$position->department->address->fullTitleCard} $position->cabinet" : null),
                    'phone' => $phone,
                    'email' => $email,
                    'themes' => $themes
                ];
            }
        }
    }
@endphp

<div class="my-4">
    <div class="grid grid-cols-2 gap-8">
        @foreach($result as $element)
            <div class="flex gap-6">
                <img class="rounded-lg max-h-[300px]" src="{{ route('Media.Image', ['path' => $element['photo_id']]) . '?h=305&w=210&fit=crop-center' }}">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-2xl font-medium text-blue-700">{{ $element['title'] }}</h4>
                        <p class="subtitle">{!! $element['subtitle'] !!}</p>
                    </div>
                    <div class="space-y-2">
                        @if(isset($element['address']) && $element['address'] != null)
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2 flex-none" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                    <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                                </svg>
                                <div>{{ $element['address'] }}</div>
                            </div>
                        @endif

                        @if(isset($element['phone']) && $element['phone'] != null)
                                @foreach((is_array(json_decode($element['phone'])) ? json_decode($element['phone']) : [json_decode($element['phone'])]) as $phone)
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2 flex-none" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                                    </svg>
                                    <div>{{ $phone }}</div>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($element['email']) && $element['email'] != null)
                            @foreach((is_array(json_decode($element['email'])) ? json_decode($element['email']) : [json_decode($element['email'])]) as $email)
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-700 mr-2 flex-none" width="27" height="27" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>
                                        <path d="M3 7l9 6l9 -6"></path>
                                    </svg>
                                    <div>{!! $email !!}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
