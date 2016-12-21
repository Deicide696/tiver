/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//       if (e==1){
//            document.getElementById("couponhasservice-service_id").disabled=false;
//            document.getElementById("couponhascategoryservice-category_service_id").disabled=true;
//       }else if(e==0){
//            document.getElementById("couponhasservice-service_id").disabled=true;
//            document.getElementById("couponhascategoryservice-category_service_id").disabled=false;
//       }


 $("#asignar").change(function(){
    var e=$("#asignar").val();
    $.ajax({
        type: "POST",
        url: "'.Url::to(['coupon/getmodel']).'",
        data: { asig: e },
        success:function (data){
            alert(data);
            $('.selector-pais select').html(data).fadeIn();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error");
        }
    }); 
 });
 
 $("#avatar-2").fileinput({
        overwriteInitial: true,
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        showBrowse: false,
        browseOnZoneClick: true,
        removeLabel: "",
        removeIcon: "<i class= 'glyphicon glyphicon-remove'></i>",
        removeTitle: "Cancel or reset changes",
        elErrorContainer: "#kv-avatar-errors-2",
        msgErrorClass: "alert alert-block alert-danger",
        defaultPreviewContent: "<img src='/uploads/default_avatar_male.jpg' alt='Your Avatar' style='width:160px'><h6 class='text-muted'>Click to select</h6>",
        layoutTemplates: {main2: "{preview} " +  btnCust + " {remove} {browse}"},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });
    
    
    var btnCust = '<button type="button" class="btn btn-default" title="Add picture tags" ' + 
    'onclick="alert(\'Call your custom code here.\')">' +
    '<i class="glyphicon glyphicon-tag"></i>' +
    '</button>'; 
$("#avatar-2").fileinput({
    overwriteInitial: true,
    maxFileSize: 1500,
    showClose: false,
    showCaption: false,
    showBrowse: false,
    browseOnZoneClick: true,
    removeLabel: '',
    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
    removeTitle: 'Cancel or reset changes',
    elErrorContainer: '#kv-avatar-errors-2',
    msgErrorClass: 'alert alert-block alert-danger',
    defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar" style="width:160px"><h6 class="text-muted">Click to select</h6>',
    layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png", "gif"]
});

