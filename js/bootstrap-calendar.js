/**
 * JQuery-Bootstrap Calendar Plugin 
 * 
 * @author Mariocoski
 * @email mariuszrajczakowski@gmail.com 
 * @github https://github.com/mariocoski/Bootstrap-calendar
 * Copyright (c) 2015 Mariocoski
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

(function ( $ ) {
  //big calendar  
  $.fn.bootstrapBigCalendar = function(options) {
    //default settings
    var settings = {
      "ajax_url"       : "cal-ajax.php", //url for retrieving the data
      // "ajax_url"       : "calendar/process.php", //url for retrieving the data
      "calendar_type"  : "big", //calendar type
      "number_of_weeks": 4, //how many weeks to display
      "first_day"      : "sunday", //or sunday
      "booking_url"    : "make_an_appointment.php", //booking url 
      "max_display"    : 7 //how many visits display in a day calendar column - default is 7
    }; 
    //extending the settings by passed options
    if(options){
      $.extend(settings, options);
    }
    //affect each calendar from certain class
    return this.each(function() {
      //get calendar_id
      var calendar_id = $(this).attr("id");
      //get id
      var id = $(this).attr("data-calendar-id");
  
        $.ajax({
          type: "POST",
          dataType: "json",
          url: settings['ajax_url'],
          data: "id="+id+"&calendar_type="+settings['calendar_type']+"&number_of_weeks="+settings['number_of_weeks']+"&first_day="+settings['first_day']+"&booking_url="+settings['booking_url']+"&max_display="+settings['max_display']+"&displaySlotType="+settings['displaySlotType'],
          success: function(json){
            if(json.success === true){
              //for debugging: console.log(json.content);
              console.log("Success1");
              $("#"+calendar_id).html(json.content); 
            }else if(json.success ===false){
              console.log("Error");
            }else{
               console.log("Error 2");
            }
          },
          error: function(json){
            console.log("failure");
            console.log(json);
          }
        });
    });
  };
}( jQuery ));