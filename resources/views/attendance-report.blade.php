<style media="screen">
li, a, tr {
  cursor:pointer !important;
}
tr:hover {
          background-color: rgb(219,221,231) !important;
        }
</style>

<div class="" style="width:60%; margin:0 auto;">
  <h2 style="margin-bottom:5px;">Daily Attendance Report</h2>
  <span style="font-size:18px;"><b>{{date('F-d-Y', strtotime($att_date))}}</b></span>
  <table width="100%">
    <thead>
      <tr style="background-color:skyblue;">
        <td>Sr</td>
        <td>Student Id</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Attendance Time</td>
        <td>Dojo Name</td>
      </tr>
    </thead>
    <tbody>

  @if(!empty($att))
  @php
  $sr = 1;
  @endphp
    @foreach($att as $r)
      <tr
      style="
      @if($loop->iteration%2 == 0)
        background-color:gainsboro;
      @endif
      ">
        <td>{{$sr++}}</td>
        <td>{{$r->login}}</td>
        <td>{{$r->firstname}}</td>
        <td>{{$r->lastname}}</td>
        <td>{{$r->attime}}</td>
        <td>{{$r->dojo_name}}</td>
      </tr>
    @endforeach
  @endif
  </tbody>
  </table>

</div>
