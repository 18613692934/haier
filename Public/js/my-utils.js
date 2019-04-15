function post(URL, PARAMS) {          
        var temp = document.createElement("form");          
        temp.action = URL;          
        temp.method = "post";          
        temp.style.display = "none";          
        for (var x in PARAMS) {          
            var opt = document.createElement("textarea");          
            opt.name = x;          
            opt.value = PARAMS[x];                 
            temp.appendChild(opt);          
        }          
        document.body.appendChild(temp);          
        temp.submit();          
        return temp;          
}          
/*时间戳转换成yyyy-MM-dd HH-mm-ss类型*/
function formatDate(now) {
    var year = now.getFullYear();
    month = getzf(now.getMonth() + 1);
    date = getzf(now.getDate());
    hour = getzf(now.getHours());
    minute = getzf(now.getMinutes());
    second = getzf(now.getSeconds());
 
return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second;
}
/*时间戳转换成yyyy-MM-dd HH-mm-ss类型*/
function formatDate1(now) {
    var newDate = new Date(now);
    newDate.setTime(now*1000);
    var year = now.getFullYear();
    month = getzf(now.getMonth() + 1);
    date = getzf(now.getDate());
    hour = getzf(now.getHours());
    minute = getzf(now.getMinutes());
    second = getzf(now.getSeconds());
 
return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second;
}
  
//补0操作,当时间数据小于10的时候，给该数据前面加一个0  
function getzf(num){  
    if(parseInt(num) < 10){  
        num = '0'+num;  
    }  
    return num;  
}  





function getDate(time){ 
    var newDate = new Date();
    newDate.setTime(time*1000);
    return newDate.toLocaleString();
} 
/**参数说明：
 * 根据长度截取先使用字符串，超长部分追加…
 * str 对象字符串
 * len 目标字节长度
 * 返回值： 处理结果字符串
 */
function cutString(str, len) {
    //length属性读出来的汉字长度为1
    if(str.length*2 <= len) {
        return str;
    }
    var strlen = 0;
    var s = "";
    for(var i = 0;i < str.length; i++) {
        s = s + str.charAt(i);
        if (str.charCodeAt(i) > 128) {
            strlen = strlen + 2;
            if(strlen >= len){
                return s.substring(0,s.length-1) + "...";
            }
        } else {
            strlen = strlen + 1;
            if(strlen >= len){
                return s.substring(0,s.length-2) + "...";
            }
        }
    }
    return s;
}




