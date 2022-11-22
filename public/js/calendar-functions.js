$(document).ready(function() {

  pageSetUp();


      "use strict";

      var date = new Date();
      var d = date.getDate();
      var m = date.getMonth();
      var y = date.getFullYear();

      var hdr = {
          left: 'title',
          center: 'month,agendaWeek,agendaDay',
          right: 'prev,today,next'
      };

      var initDrag = function (e) {
          // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
          // it doesn't need to have a start or end

          var eventObject = {
              title: $.trim(e.children().text()), // use the element's text as the event title
              description: $.trim(e.children('span').attr('data-description')),
              icon: $.trim(e.children('span').attr('data-icon')),
              className: $.trim(e.children('span').attr('class')) // use the element's children as the event class
          };
          // store the Event Object in the DOM element so we can get to it later
          e.data('eventObject', eventObject);

          // make the event draggable using jQuery UI
          e.draggable({
              zIndex: 999,
              revert: true, // will cause the event to go back to its
              revertDuration: 0 //  original position after the drag
          });
      };

      var addEvent = function (title, priority, description, icon) {
          title = title.length === 0 ? "Untitled Event" : title;
          description = description.length === 0 ? "No Description" : description;
          icon = icon.length === 0 ? " " : icon;
          priority = priority.length === 0 ? "label label-default" : priority;

          var html = $('<li><span class="' + priority + '" data-description="' + description + '" data-icon="' +
              icon + '">' + title + '</span></li>').prependTo('ul#external-events').hide().fadeIn();

          $("#event-container").effect("highlight", 800);

          initDrag(html);
      };

      /* initialize the external events
     -----------------------------------------------------------------*/

      $('#external-events > li').each(function () {
          initDrag($(this));
      });

      $('#add-event').click(function () {
          var title = $('#title').val(),
              priority = $('input:radio[name=priority]:checked').val(),
              description = $('#description').val(),
              icon = $('input:radio[name=iconselect]:checked').val();

          addEvent(title, priority, description, icon);
      });

      /* initialize the calendar
     -----------------------------------------------------------------*/

    var calendar =   $('#calendar').fullCalendar({

          header: hdr,
          droppable: true,
          selectable: true,
          selectHelper: true,
          events: "show",
          nextDayThreshold: '00:00:00',
          select: function (start, end, allDay) {
           start=moment(start).format('Y-MM-DD HH:mm');
           end = new Date(end);
           end.setDate(end.getDate());
           end=moment(end).format('Y-MM-DD HH:mm');
           addNewEvent(start, end, 0);
         },


         eventDrop: function (event, delta) {
           start=moment(event.start).format('Y-MM-DD HH:mm:ss');
           end=moment(event.end).format('Y-MM-DD HH:mm:ss');
           $.ajax({
               url: 'update',
               data: {
                   title: event.title,
                   start: start,
                   end: end,
                   id: event.id,
                   type: 'update'
               },
               type: "POST",
               success: function (response) {
                   _success('Event Updated Successfully');
               }
           });
       },
                   eventClick: function (event) {
                     addNewEvent('', '', event.id);
                   },
          eventRender: function (event, element, icon) {
              if (event.allDay === 'true') {
                         event.allDay = true;
                 } else {
                         event.allDay = false;
                 }
          },

          windowResize: function (event, ui) {
              $('#calendar').fullCalendar('render');
          }
      });

      /* hide default buttons */
      $('.fc-right, .fc-center').hide();


    $('#calendar-buttons #btn-prev').click(function () {
        $('.fc-prev-button').click();
        return false;
    });

    $('#calendar-buttons #btn-next').click(function () {
        $('.fc-next-button').click();
        return false;
    });

    $('#calendar-buttons #btn-today').click(function () {
        $('.fc-today-button').click();
        return false;
    });

    $('#mt').click(function () {
        $('#calendar').fullCalendar('changeView', 'month');
    });

    $('#ag').click(function () {
        $('#calendar').fullCalendar('changeView', 'agendaWeek');
    });

    $('#td').click(function () {
        $('#calendar').fullCalendar('changeView', 'agendaDay');
    });

});
function myDataTable()
{

  $('#mydatatable').DataTable().destroy();
  var otable =  $('#mydatatable').DataTable(
  		{
  			"bPaginate": false,
        "ordering" : false
  		}
  );
  $("#mydatatable thead th input[type=text]").on( 'keyup change', function () {

      otable
          .column( $(this).parent().index()+':visible' )
          .search( this.value )
          .draw();

  });

}
function check_all(state)
{
  var state = $(state).is(':checked');
    $(".student_id").prop("checked", state);
}


function saveEvent()
{
	$("#event_form").on('submit',(function(e){
    let formAction = $(this).attr('action');
  	$(".overlay").show();
  	$("button.save").prop("disabled",true);
		var edit_id = $("input[name=id]").val();
     e.preventDefault();
  		$.ajax({
  			url: formAction, // Url to which the request is send
  			type: "POST",             // Type of request to be send, called as method
  			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
  			contentType: false,       // The content type used when sending data to the server.
  			cache: false,             // To unable request pages to be cached
  			processData:false,        // To send DOMDocument or non processed data file it is set to false
  			success: function(response)   // A function to be called if request succeeds
  			{
  				$( "#event_form" ).unbind( "submit");
  				if(response['success']==1)
 					{
  					_success(response['msg']);
						$("#event_modal").modal('hide');
  					$("button.save").prop("disabled",false);
  					var data = response['data'];
         //    var response_data = response['data'];
  					var id = data['id'];
         //    $("#calendar").fullCalendar('removeEvents', response_data['id']);
         //    $("#calendar").fullCalendar('renderEvent',
         //    {
         //      id:    response_data['id'],
         //      start: response_data['start'],
         //      end: response_data['end'],
         //      color: response_data['color'],
         //      ename: response_data['title'],
         //      title: response_data['title']
         //
         // },true);
         //    $("#calendar").fullCalendar('unselect');
         $('#calendar').fullCalendar('refetchEvents');
  					$(".overlay").hide();
  				}
  				else
  				{
  					var error = "";
  					for(var a = 0; a<response['msg'].length; a++)
  					{
  						error += response['msg'][a] + "<br/>";
  					}
  					_error(error);
  					$("button.save").prop("disabled",false);
  					$(".overlay").hide();
  				}
  			}
  		});
   }));
}
function addNewEvent(start='', end='', id)
{
  $.ajax({
    url: 'add',
    method: 'post',
    data: {start:start, end:end, id:id},
    success: function(response)
    {
     $("#event_modal_detail").html(response.html);
     $("#event_modal").modal('show');
     myDataTable();
    }
  });
}


function deleteEvent(id)
{
	$.SmartMessageBox({
		title : "Warning!",
		content : "Are you sure ? Do you want to delete this?",
		buttons : '[No][Yes]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Yes") {
			$(".overlay").show();
			var formAction = "delete";
		 $.post(formAction,{id:id},function(data){
			 if(data['success'] == 1)
			 {
        $('#calendar').fullCalendar('refetchEvents');
         $("#event_modal").modal('hide');
				 _success(data['msg']);
			 }
			 else
			 {
				 _error(data['msg']);
			 }
			 $(".overlay").hide();
		 });
		}
		if (ButtonPressed === "No")
		{
		 _error('You pressed No');
		}
	});
}
function refetch_events(dojo_id)
{
  var events = {
          url: "show",
          type: 'get',
          data: {
              dojo_id: dojo_id
          }
      }
      $('#calendar').fullCalendar('removeEventSource', events);
      $('#calendar').fullCalendar('addEventSource', events);
      // $('#calendar').fullCalendar('refetchEvents');
}


function change_date(start_date)
{
    start_date = new Date(start_date);
    start_date = new Date(start_date.getTime() + 30*60000);
    start_date=moment(start_date).format('Y-MM-DDTHH:mm');
    $("#end").val(start_date);
}
