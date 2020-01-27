import {Toast} from 'vant'
window.addEventListener('offline',  function() {
    Toast("你已掉线");
});
window.addEventListener('online',  function() {
    Toast("已连接");
});