import Conf from '../conf.js'
let http = {
    post: function(uri = null, formParams = {}, isAsync = true, successBack, errorBack){
        let url = Conf.apiUrl + uri;
        let res = {};
        $.ajax({
            url: url,
            data: formParams,
            method: Conf.ajax.method,
            async: isAsync,
            datatype: Conf.ajax.dataType,
            success: function(data){
                if(data.code !== Conf.ajax.normalCode){
                    errorBack()
                }else{
                    let _data = data.data;
                    if(_data.status !== Conf.ajax.successStatus){
                        errorBack()
                    }else{
                        res = _data;
                        successBack(res)
                    }
                }
            },
            error: function () {
                errorBack()
            }
        });
        return res
    }
};
export default http