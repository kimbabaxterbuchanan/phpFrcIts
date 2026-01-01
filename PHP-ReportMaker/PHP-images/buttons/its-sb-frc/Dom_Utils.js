var DHTML = (document.getElementByName || document.getElementById || document.all || document.layers);
var restorePage = "";

var keycnt = 0;
var keysep = 0;
var usingSep = "";

function refreshDocUserInfo(formType) {
    document.forms["form_"+formType].submit();
}

function refreshDoc(phpObj, formType, status) {
    if ( typeof phpObj != 'object') 
        phpObj = new getObj(phpObj);
//    var selIdx = phpObj.selectedIndex;
//    var userId = phpObj.options[selIdx].value;
//    var ref = document.getElementById("refresh");
//    ref.value="refresh";
        obj = new getObj("postForm");
        obj.value="";
    document.forms[formType+"Form"].submit();
//    document.location.href="FRCForm.php?formType="+formType+"&userId="+userId+"&status="+status;
//    document.forms[0].submit();
}

function setStatus(obj,stat1,stat2) {
    var obj1 = new getObj("status");
    if ( obj.checked ) {
        obj1.value=stat2;
    } else {
        obj1.value=stat1;
    }
}

        function setSubTotals(field1, field2, field3, act, adj1, adj2) {
            var obj1 = new getObj(field1);
            var obj2 = new getObj(field2);
            var obj3 = new getObj(field3);
            
            var val = 0;
            
            var st1 = obj1.value;
            var st2 = obj2.value;
            
            if ( st1 == "" ) { st1 = "0" };
            if ( st2 == "" ) { st2 = "0" };
            
            if ( st1.indexOf(".") == -1 && adj1 == "" ) {
                st1 += ".00";
                obj1.value=st1;
            }
            if ( st2.indexOf(".") == -1 && adj2 == "" ) {
                st2 += ".00";
                obj2.value=st2;
            }
            var div = 1000;
            if ( adj1 == "" ) {
                st1 = st1 * 1000;
            }
            if ( adj2 == "" ) {
                st2 = st2 * 1000;
                if ( act != "add" && act != "sub"  ) {
                    div*=1000;
                }
            }
            if ( act == "add" ) {
                val = parseInt(st1) + parseInt(st2);
            }
            if ( act == "sub" ) {
                val = parseInt(st1) - parseInt(st2);
            }
            if ( act == "div" ) {
                val = parseInt(st1) / parseInt(st2);
            }
            if ( act == "mult" ) {
                val = parseInt(st1) * parseInt(st2);
            }
            if ( act == "diem" ) {
                if ( adj1 != "" ) {
                    if ( st1 == 1 ) {
                        val = parseInt(st1) * ( parseInt(st2) * .75 );
                    } else if ( st1 == 2 ) {
                        val = parseInt(st1) * ( parseInt(st2) * .75 );
                    } else {
                        var st11 = parseInt(st1) - 2;
                        val = ( 2 * ( parseInt(st2) * .75 ) ) + ( parseInt(st11) * parseInt(st2) );
                    }
                } else if ( adj2 != "" ) {
                    if ( st2 == 1 ) {
                        val = parseInt(st2) * ( parseInt(st1) * .75 );
                    } else if ( st2 == 2 ) {
                        val = parseInt(st2) * ( parseInt(st1) * .75 );
                    } else {
                        var st22 = parseInt(st2) - 2;
                        val = ( 2 * ( parseInt(st1) * .75 ) ) + ( parseInt(st22) * parseInt(st1) );
                    }
                }
            }
            val = val/div;
            obj3.value = val.toFixed(2);
            setTotal();
        }

        function setTotal(){
            var obj1 = new getObj("total_per_diem");
            var obj2 = new getObj("total_travel_fare");
            var obj3 = new getObj("total_auto_rental");
            var obj4 = new getObj("total_auto_gas");
            var obj5 = new getObj("total_pov");
            var obj6 = new getObj("total_lodging");
            var obj7 = new getObj("total_parking_fees");
            var obj8 = new getObj("total_conference_fees");
            var obj9 = new getObj("total_taxicab_fees");
            var obj10 = new getObj("total");
            var obj11 = new getObj("travel_advance_request");

            var st1 = obj1.value;
            var st2 = obj2.value;
            var st3 = obj3.value;
            var st4 = obj4.value;
            var st5 = obj5.value;
            var st6 = obj6.value;
            var st7 = obj7.value;
            var st8 = obj8.value;
            var st9 = obj9.value;
            var st11 = obj11.value;
            if ( st1 == "" ) {
                st1 = "0.00";
                obj1.value=st1;
            } else {
                if ( st1.indexOf(".") == -1 ){
                    obj1.value=st1+".00";
                }
            }

            if ( st2 == "" ) {
                st2 = "0.00";
                obj2.value=st2;
            } else {
                if ( st2.indexOf(".") == -1 ) {
                    obj2.value=st2+".00";
                }
            }

            if ( st3 == "" ) {
                st3 = "0.00";
                obj3.value=st3;
            } else {
                if ( st3.indexOf(".") == -1 ) {
                    obj3.value=st3+".00";
                }
            }
            if ( st4 == "" ) {
                st4 = "0.00";
                obj4.value=st4;
            } else {
                if ( st4.indexOf(".") == -1 ) {
                    obj4.value=st4+".00";
                }
            }
            if ( st5 == "" ) {
                st5 = "0.00";
                obj5.value=st5;
            } else {
                if ( st5.indexOf(".") == -1 ) {
                    obj5.value=st5+".00";
                }
            }
            if ( st6 == "" ) {
                st6 = "0.00";
                obj6.value=st6;
            } else {
                if ( st6.indexOf(".") == -1 ) {
                    obj6.value=st6+".00";
                }
            }
            if ( st7 == "" ) {
                st7 = "0.00";
                obj7.value=st7;
            } else {
                if ( st7.indexOf(".") == -1 ) {
                    obj7.value=st7+".00";
                }
            }
            if ( st8 == "" ) {
                st8 = "0.00";
                obj8.value=st8;
            } else {
                if ( st8.indexOf(".") == -1 ) {
                    obj8.value=st8+".00";
                }
            }
            if ( st9 == "" ) {
                st9 = "0.00";
                obj9.value=st9;
            } else {
                if ( st9.indexOf(".") == -1 ) {
                    obj9.value=st9+".00";
                }
            }
            if ( st11 == "" ) {
                st11 = "0.00";
                obj11.value=st11;
            } else {
                if ( st11.indexOf(".") == -1 ) {
                    obj11.value=st11+".00";
                }
            }
            st1 = st1*100;
            st2 = st2*100;
            st3 = st3*100;
            st4 = st4*100;
            st5 = st5*100;
            st6 = st6*100;
            st7 = st7*100;
            st8 = st8*100;
            st9 = st9*100;

            var stotal = parseInt(st1)+parseInt(st2);
            stotal += parseInt(st3)+parseInt(st4);
            stotal += parseInt(st5)+parseInt(st6);
            stotal += parseInt(st7)+parseInt(st8);
            stotal += parseInt(st9);

            var $tTotal =(stotal/100)-parseInt(obj11.value); 
            obj10.value = $tTotal.toFixed(2);
            }

function checkkey(obj, ele, evt) {
    var alphaStr = "ABCDEFGJLMNOPRSTUVY\-";

    var key = window.event ? evt.keyCode : evt.which;
    if ( key == 13 || key == 0 || key == 8 || key == 45 ) {
            return true;
    } else {
            var keychar = String.fromCharCode(key);
            reg = /\d/;

            if ( ele == "date" ) {
                var txt = obj.value;
                if ( alphaStr.indexOf(keychar) > -1 ) {
                    return true;
                } if ( ! reg.test(keychar) ) {
                    alert("Dates format is 11-JAN-2008");
                    return false;
                } else {
                    return true;
                }

            } else {
                if ( ! reg.test(keychar) ) {
                    if ( ele == "ssn" ) {
                        alert("Allowed format is 111223333 , 111-22-3333");
                    } else if ( ele == "phone" ) {
                        alert("Phone format example 1112223333 , 111-222-3333");
                    } else if ( ele == "workphone" ) {
                        alert("Phone format example 111222333344444 , 111-222-3333 x44444");
                    }
                    return false;
                }
            }
            return true;
            cnt++;
    }
}

function sleep(naptime, controlFunction){
    naptime = naptime * 1000;
    var sleeping = true;
    var now = new Date();
    var alarm;
    var startingMSeconds = now.getTime();
    while(sleeping){
        alarm = new Date();
        alarmMSeconds = alarm.getTime();
        if(alarmMSeconds - startingMSeconds > naptime){ sleeping = false; }
    }
}

// this function is called if the Ajax call succeeds.

function parseXML(DOC,tag,fnd) {
    var nodevalue = "";
    RemoveTextNodes(DOC);
    for (var i=0; i < DOC.childNodes.length; i++) {
        var Node=DOC.childNodes[i];
        if (Node.nodeType == TEXT_NODE)	{
            if ( fnd  ) {
                return Node.nodeValue;
            }
        } else {
            if ( Node.nodeName == tag )
                fnd = true;
        }
        if (Node.hasChildNodes()) {
            nodevalue = parseXML(Node,tag,fnd);
            return nodevalue;
        }
    }
    return nodevalue;
}    

function emptyElement(elementId) {
    var label= elementId;
    if ( typeof(elementId) == "string" )
        label=document.getElementById(elementId);
    while( label.hasChildNodes() ) { label.removeChild( label.lastChild ); }
}

function processREQ(xmlInfo, xmlType, xsltFile ) {
    var xml = Sarissa.getDomDocument();
    var xslt = Sarissa.getDomDocument();
    xml.async = false;
    xslt.async = false;
    var xml = Sarissa.getDomDocument();
    var domparser = new DOMParser();
    if ( xmlType == 'string') {
        xml = domparser.parseFromString(xmlInfo,"text/xml");
    } else {
        xml.load(xmlInfo);
    }
    xslt.load(xsltFile);
    var processor = new XSLTProcessor();
    processor.importStylesheet(xslt);
    var XmlDom = processor.transformToDocument(xml)
    
    var serializer = new XMLSerializer();
    var outstr = serializer.serializeToString(XmlDom.documentElement);
    return outstr;
}

function displayDom(outstr,elem,val) {
    html2dom.getDOM(outstr, elem, val);
    eval(html2dom.result);
}

/**
 *
 *  URL encode / decode
 *  http://www.webtoolkit.info/
 *
 **/

var Url = {
    
    // public method for url encoding
    encode : function (clearString) {
        //        return escape(this._utf8_encode(string));
        var output = '';
        var x = 0;
        clearString = clearString.toString();
        var regex = /(^[a-zA-Z0-9_.]*)/;
        while (x < clearString.length) {
            var match = regex.exec(clearString.substr(x));
            if (match != null && match.length > 1 && match[1] != '') {
                output += match[1];
                x += match[1].length;
            } else {
                if (clearString[x] == ' ')
                    output += '+';
                else {
                    var charCode = clearString.charCodeAt(x);
                    var hexVal = charCode.toString(16);
                    if (hexVal.length < 2)
                        hexVal = "0"+hexVal;
                    output += '%' + hexVal.toUpperCase();
                }
                x++;
            }
        }
        return output;
    },
    
    // public method for url decoding
    decode : function (encodedString) {
        //        return this._utf8_decode(unescape(string));
        var output = encodedString;
        var binVal, thisString;
        var myregexp = /(%.{2})/;
        while ((match = myregexp.exec(output)) != null 
        && match.length > 1 
        && match[1] != '') {
            binVal = parseInt(match[1].substr(1),16);
            thisString = String.fromCharCode(binVal);
            output = output.replace(match[1], thisString);
        }
        var clearText = '';
        //        output = '';
        var x = 0;
        while (x < output.length) {
            if (output.substr(x,1) == '+') {
                clearText += ' ';
            } else {
                clearText += output.substr(x,1);
            }
            x++;
        }
        return clearText;
    },
    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"");
        var utftext = "";
        
        for (var n = 0; n < string.length; n++) {
            
            var c = string.charCodeAt(n);
            
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            
        }
        
        return utftext;
    },
    
    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
        utftext = utftext.replace(/\r\n/g,"");
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        
        while ( i < utftext.length ) {
            
            c = utftext.charCodeAt(i);
            
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
            
        }
        
        return string;
    }
}

function readCookie(cookieName){
    var searchName = cookieName + "=";
    if ( typeof document.cookie == "undefined")
        document.cookie="";
    var cookies = document.cookie;
    var start = cookies.indexOf(searchName);
    if (start == -1){ // cookie not found
        return "";
    }
    start += searchName.length //start of the cookie data;
    var end = cookies.indexOf(";", start);
    if (end == -1){
        end = cookies.length;
    }
    return cookies.substring(start, end);
}

        function hide() {
        // Used in many places
        // Makes specified objects invisible.  May receive multiple objects or object ids as arguments.
          if (!DHTML) return;
          for(var i=0; i<arguments.length; i++) {
            var x = new getObj(arguments[i]);
            x.style.visibility = 'hidden';
            x.style.display = 'none';
          }
        }

         function toggleVisibility(vName,hiddenlbl,hiddentitle,showlbl,showtitle) {
            var divName=vName+"Div";
            var txtName=vName+"DivTxt";
            var lblName=vName+"Txt";
            var titleName=vName+"Title";
            var obj = document.getElementById(divName);
            var lbl = "";
            var title = "";
            if ( obj.style.visibility == "hidden" ) {
                showBlock(divName);
                lbl=hiddenlbl+" ";
                title=hiddentitle;
            } else {
                hideBlock(divName);
                lbl=showlbl+" ";
                title=showtitle;
            }
            var x = document.getElementById(txtName);
            x.value=obj.style.visibility;
            x = document.getElementById(lblName);
            x.innerHTML=lbl;
            x = document.getElementById(titleName);
            x.innerHTML=title;
            
        }

      function show() {
        // Used in many places
        // Makes specified objects visible.  May receive multiple objects or object ids as arguments.
          if (!DHTML) return;
          for(var i=0; i<arguments.length; i++){
            var x = new getObj(arguments[i]);
            x.style.visibility = 'visible';
            x.style.display = 'block';
          }
        }

        function hideBlock() {
        // Used in many places
        // Makes specified objects invisible.  May receive multiple objects or object ids as arguments.
          if (!DHTML) return;
          for(var i=0; i<arguments.length; i++) {
            var x = new getObj(arguments[i]);
            x.style.visibility = 'hidden';
          }
        }

        function showBlock() {
        // Used in many places
        // Makes specified objects visible.  May receive multiple objects or object ids as arguments.
          if (!DHTML) return;
          for(var i=0; i<arguments.length; i++){
            var x = new getObj(arguments[i]);
            x.style.visibility = 'visible';
          }
        }

        function getObj(name) {
        // Used in Hide, Match Position and Show
        // Returns an object given its name
            if(typeof name == "object"){
                this.obj=name;
                this.style=name.style;
            } else {
                var elems = document.all || document.getElementsByTagName('*');
                for ( i = 0; i < elems.length; i++ ) {
                    
                    if ( elems[i].name == name || elems[i].id == name ) {
                        this.obj = elems[i];
                        this.style= elems[i].style;
                        break;
                    }
                }
           }
           return this.obj;
        }
 function loadImage(im){
         var i=new Image();
         i.src='wbimg/img'+im;return i;
}

var cb = '';

function getBtnVal () {
    obj = document.forms['FRCCareer'].btnVal;
    if ( obj.value == "" ) obj.value="0c";
    btnSelect(obj.value);
//    cb = obj.value;
}
function msOver(id){
  x=id.substring(0,id.length-1);
  y=cb.substring(0,cb.length-1);
  if ( x != y )
    document['btn'+x].src=eval('btn'+id+'.src');
    if(id.indexOf('e')!=-1)
        document['btn'+x+'e'].src=eval('btn'+id+'e.src');
}
function msOut(id){
  x=id.substring(0,id.length-1);
  y=cb.substring(0,cb.length-1);
  yy=cb.substring(cb.length-1);
  if ( x != y  )
    document['btn'+x].src=eval('btn'+id+'.src');
    if(id.indexOf('e')!=-1)
        document['btn'+x+'e'].src=eval('btn'+id+'e.src');
}
function btnSelect(id){
    x=id.substring(0,id.length-1);
    document['btn'+x].src=eval('btn'+id+'.src');
    if ( cb != "" && cb != id ) {
        y=cb.substring(0,cb.length-1);
        document['btn'+y].src=eval('btn'+y+'n.src');
        }
    cb = id;
    if(id.indexOf('e')!=-1)
        document['btn'+x+'e'].src=eval('btn'+id+'e.src');
}
