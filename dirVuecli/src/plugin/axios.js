import Axios from 'axios'
import Conf from './config'

let qs = require("qs");

let apiUrl = Conf.data.apiUrl;

Axios.defaults.baseURL = apiUrl;
Axios.defaults.headers['Content-Type'] ='application/x-www-form-urlencoded';
Axios.defaults.withCredentials = false;
Axios.defaults.transformRequest = function(data){
    return qs.stringify(data)
};

export default Axios