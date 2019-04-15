/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function showhide_obj(obj,icon){
        obj = document.getElementById(obj);
        
        icon = document.getElementById(icon);
        if(obj.style.display == "none"){
            //指定文档中的对象为div，仅适用于IE；
            div_list = document.getElementsByTagName("thead");
            for (var i = 0; i < div_list.length; i++) {
                thisDiv = div_list[i];
                //当文档div中的id含有list时，与charAt类似
                if(thisDiv.id.indexOf("title") != -1){
                    //循环把所有的菜单链接都隐藏起来
                    thisDiv.style.display = "none";
                    icon.innerHTML = "&#xe6d5;";
                }
            }
            
            myfont = document.getElementsByTagName("font");
            for (var i = 0; i < myfont.length; i++) {
                thisfont = myfont[i];
            }
            icon.innerHTML = "&#xe6d6;";
            obj.style.display = "";//只显示当前链接
        } else {
            //当前对象是打开的，就封闭他
            icon.innerHTML = "&#xe6d5;";
            obj.style.display = "none";
            
        }
    }

