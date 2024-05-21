var FilterHandler = function(config){
    console.log("CALLED");
    this.getparams = function(){
        var params = config.sumberData.split('?');
        if(params.length > 1)
            params = params[1].split('&');
        var temp = {};
        if(params.length > 0){
            params.forEach(p => {
                var obj = p.split('=');
                temp[obj[0]] = obj[1];
            });
        }

        return temp;
    }
    $('#status-verifikasi').on('change', function(){
        var ini = $(this);
        var val = ini.val();
        var origin = config.sumberData.split('?')[0];

        location.href = path + 'dashboard?v=' + val;
    });
};