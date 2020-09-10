$(document).ready(function(){
$(".action-work-btn").submit(function(e){
   var action_value = $(this).attr( 'metavalue' );
   var control = $(this).text();
   console.log(control);
   if ($(this).children('svg').length === 0){
         var job_id = $(this).attr( 'job_id' );
         $(this).html('<div id="'+job_id+'"><i class="fa fa-circle-notch fa-spin float-right"></i></div>');
         if (typeof job_id !== typeof undefined && job_id !== false) {
            $.ajax({
             url: "../functions/job_mute.php",
             type: "post",
             data: {
               job_id: job_id,
               action: control
             },
           }).done(function(result){
             var code = result.code;
             console.log(result.status);
             $('#'+job_id).html(code);
             if(code == 'Mute'){
               $('#'+job_id).css("color",'#fff');
             }else{
               $('#'+job_id).css("color",'#CBCBCB');
             }


           })
          }


       }

 });
});
