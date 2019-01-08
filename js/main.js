$(function() {
  $("#nav_layerlist").click(function() {
      $("#sidebar").toggle();
      $("#accordion").accordion("option", { active: 0 });
      updateSize();
  });
  
  $("#btn-hide").click(function() {
      $("#sidebar").hide();
      updateSize();
  });
  
  $('#calBtn').click(function(){
	  $('#dialog1').modal('show')
  });
  
  $('#cancelBtn').click(function(){
	  $('#dialog1').modal('hide')
  });

   $("#download_all").click(function() {
     $.get("download.php",function(data,status){
       alert("Data:"+ data + "\nStatus: " + status);
      });
  });
  
  $(window).resize(function() {
      updateSize();
  });  
});

updateSize();

function updateSize(){
  $("#container").css("height", $(window).height() - $("nav").height());
  $("#accordion").accordion({        //jQuery UI 
      heightStyle: "fill",
  });
   map.updateSize();
  $("#accordion").accordion("refresh");   
}


/*
// get value from myNavbar
//inputGroupSelect01(weekday),02(time in day),03(month)
var weekday=$('#inputGroupSelect01').val();
var inDay=$('#inputGroupSelect02').val();
var month=$('#inputGroupSelect03').val();
*/





