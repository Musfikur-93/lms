@extends('instructor.instructor_dashboard')
@section('instructor')

@php
    $id = Auth::user()->id;
    $profileData = App\Models\User::find($id);
@endphp

<div class="page-content">
    <div class="chat-wrapper">
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <div class="d-flex align-items-center">
                    <div class="chat-user-online">
                        <img src="{{ (!empty($profileData->photo)) ? url('upload/instructor_images/'.$profileData->photo) : url('upload/no_image.jpg') }}" width="45" height="45" class="rounded-circle" alt="" />
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0">{{ $profileData->name }}</p>
                        <p class="mb-0 chat-msg">Instructor</p>
                    </div>
                </div>
                <div class="mb-3"></div>
            </div>
            <div class="chat-sidebar-content">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-Chats">
                        <div class="p-3">
                            <div class="dropdown mt-3"> <a href="#" class="text-uppercase text-secondary dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">Recent Chats <i class='bx bxs-chevron-down'></i></a>
                            </div>
                        </div>
                        <div class="chat-list">
                            <div class="list-group list-group-flush">
                                <a href="javascript:;" class="list-group-item">
                                    <div class="d-flex">
                                        <div class="chat-user-online">
                                            <img src="{{ (!empty($question->user->photo)) ? url('upload/user_images/'.$question->user->photo) : url('upload/no_image.jpg') }}" width="42" height="42" class="rounded-circle" alt="" />
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">{{ $question['user']['name'] }}</h6>
                                            <p class="mb-0 chat-msg">Student</p>
                                        </div>
                                        <div class="chat-time">{{ Carbon\Carbon::parse($question->created_at)->diffForHumans() }}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-header d-flex align-items-center">
            <div class="chat-toggle-btn"><i class='bx bx-menu-alt-left'></i>
            </div>
            <h6>{{ $question['course']['course_name'] }}</h6>
        </div>

        <div class="chat-content">
            <div class="chat-content-leftside">
                <div class="d-flex">
                    <img src="{{ (!empty($question->user->photo)) ? url('upload/user_images/'.$question->user->photo) : url('upload/no_image.jpg') }}" width="48" height="48" class="rounded-circle" alt="" />
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0 chat-time">{{ $question->subject }}, {{ Carbon\Carbon::parse($question->created_at)->diffForHumans() }}</p>
                        <p class="chat-left-msg">{{ $question->queston }}</p>
                    </div>
                </div>
            </div>

            @foreach ($reply as $rep)

            <div class="chat-content-rightside">
                <div class="d-flex ms-auto">
                    <div class="flex-grow-1 me-2">
                        <p class="mb-0 chat-time text-end">you, {{ Carbon\Carbon::parse($rep->created_at)->diffForHumans() }}</p>
                        <p class="chat-right-msg">{{ $rep->queston }}</p>
                    </div>
                </div>
            </div>

            @endforeach

        </div>

        <form action="{{ route('instructor.replay') }}" method="post">
            @csrf

            <input type="hidden" name="qid" value="{{ $question->id }}">
            <input type="hidden" name="course_id" value="{{ $question->course->id }}">
            <input type="hidden" name="user_id" value="{{ $question->user->id }}">
            <input type="hidden" name="instructor_id" value="{{ $profileData->id }}">

            <div class="chat-footer d-flex align-items-center">
                <div class="flex-grow-1 pe-2">
                    <div class="input-group"><span class="input-group-text"></span>
                        <input type="text" name="queston" class="form-control" placeholder="Type a message">
                    </div>
                </div>
                <div class="chat-footer-menu">
                    <div class="chat-footer-menu">
                        <button type="submit"><i class="lni lni-reply"></i>Send </button>
                    </div>
                </div>
            </div>
        </form>
        <!--start chat overlay-->
        <div class="overlay chat-toggle-btn-mobile"></div>
        <!--end chat overlay-->
    </div>
</div>



@endsection
