const getblblData = function(myid,sessdata,reset){
    $.ajax({
        url:'./bilibili_data.php',
        data:{uid:1526121443,sessdata:'5dd1d067%2C1706514198%2Cce968%2A81hZg6uLVTkDrCcmU1QGTSKhcTFzieUTCKQwqjm60M1f-SFbWTC1FukfbIps6Azd9-iW6JFwAAKwA'},
        dataType:'json',
        type:'POST',
        success:function(res){
            for(var i = 0; i < Object.keys(reset).length; i++){
                Object.values(reset)[i]
            }
            $("#bzhan-user-tougao").text(res.tougao);
            $("#bzhan-user-fensi").text(res.follwer);
            $("#bzhan-user-bofang").text(res.view);
            $("#bzhan-user-huozan").text(res.likes);
        }
    });
}