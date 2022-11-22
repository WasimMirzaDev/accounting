<nav>
  <ul>
    <li>
      <a href="{{route('home')}}" title="User"><span id="dashboard_menu" class="menu-item-parent">Dashboard</span></a>
    </li>
    <li>
      <a href="{{route('students.show')}}" title="Students"><span id="students_menu" class="menu-item-parent">Manage Students</span></a>
    </li>
    <li>
      <a href="{{route('events.show')}}" title="Manage Events"><span id="events_menu" class="menu-item-parent">Calender & Events</span></a>
    </li>
    
    <li id="1" class="">
       <a href="javascript:" title="Attendance"><i class="fa fa-edit"></i><span class="menu-item-parent">Attendance</span><b class="collapse-sign"><em class="fa fa-plus-square-o"></em></b></a>
       <ul style="display: none;">
        <li>
      <a href="{{route('grading-policy.show')}}" title="Attendance Ranking"><span id="attendance_ranking_menu" class="menu-item-parent">Attendance Ranking Policy</span></a>
    </li>
    <li>
      <a href="{{route('attendance.upload')}}" title="Upload Attendance"><span id="attendance_menu" class="menu-item-parent">Upload Attendance</span></a>
    </li>
    <li>
      <a href="{{route('attendance.student')}}" title="Student Attendance"><span id="student_attendance_menu" class="menu-item-parent">Student Attendance</span></a>
    </li>
    <li>
      <a href="{{route('attendance.daily')}}" title="Daily Attendance"><span id="attendance_daily_menu" class="menu-item-parent">View Daily Attendance</span></a>
    </li>
    <li>
      <a href="{{route('attendance.attendance-summary')}}" title="Attendance Summary"><span id="attendance_summary_menu" class="menu-item-parent">View Attendance Summary</span></a>
    </li>
       </ul>
    </li>


  </ul>
</nav>


