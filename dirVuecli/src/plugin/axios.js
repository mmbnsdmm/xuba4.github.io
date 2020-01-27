import Axios from 'axios'
import Conf from './config'

let qs = require("qs");

let apiDomain = Conf.data.apiDomain;

Axios.defaults.baseURL = apiDomain;
Axios.defaults.headers['Content-Type'] ='application/x-www-form-urlencoded';
Axios.defaults.withCredentials = false;
Axios.defaults.transformRequest = function(data){
    return qs.stringify(data)
};

export default Axios