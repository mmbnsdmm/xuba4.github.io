import Conf from '../conf.js'
let http = {
    syncPost: function(uri = null, formParams = {}, isAsync = true){
        let url = Conf.apiUrl + uri
        let res = {}
        $.ajax({
            url: url,
            data: formParams,
            method: Conf.ajax.method,
            async: isAsync,
            datatype: Conf.ajax.dataType,
            success: function(data){
                if(data.code !== Conf.ajax.normalCode){}else{
                    let _data = data.data
                    if(_data.status !== Conf.ajax.successStatus){}else{
                        res = _data
                    }
                }
            }
        })
        return res
    }
}
export default http