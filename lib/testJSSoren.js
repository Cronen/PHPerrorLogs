
function run_script(){
    console.log("run_script",$("#refreshbtn span"));
    
    
//    if($("#refreshbtn span").hasClass("gly-spin"))
//    {
//        $("#refreshbtn span").removeClass("gly-spin");
//        // DO SOME JUMP OVER AND EXIT HERE
//        alert("Scriptet ER opcdateret");
//        
//    };
   
    $("#refreshbtn span").addClass('gly-spin');
    console.log("script running: ",$("#refreshbtn span"));
    
    setTimeout("myFunction()",10);
    
}
function myFunction()
{
    $.ajax({ 
    type: 'GET',
    dataType: "text",
    url: 'script/log_handler.php',
    async: false,

    success: function (data) {
       
         $("#refreshbtn span").removeClass('gly-spin');
         alert(data);
    },
    error: function(data){
    
        alert('Error: '+data);
         $("#refreshbtn span").removeClass('gly-spin');
    } 
    });
    
}
