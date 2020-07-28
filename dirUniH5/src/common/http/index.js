import Conf from '../conf.js'
import jquery from 'jquery'
let http = {
    syncPost: function(uri = null, formParams = {}){
        let url = Conf.apiUrl + uri
        let res = {}
        jquery.ajax({
            url: url,
            data: formParams,
            method: Conf.ajax.method,
            async: Conf.ajax.async,
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