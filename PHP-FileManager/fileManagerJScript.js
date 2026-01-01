        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        function Is(){
        this.appname = navigator.appName;
        this.appversion = navigator.appVersion;
        this.platform = navigator.platform;
        this.useragent = navigator.userAgent.toLowerCase();
        this.ie = ( this.appname == 'Microsoft Internet Explorer' );
        if (( this.useragent.indexOf( 'mac' ) != -1 ) || ( this.platform.indexOf( 'mac' ) != -1 )){
        this.sisop = 'mac';
        } else if (( this.useragent.indexOf( 'windows' ) != -1 ) || ( this.platform.indexOf( 'win32' ) != -1 )){
        this.sisop = 'windows';
        } else if (( this.useragent.indexOf( 'inux' ) != -1 ) || ( this.platform.indexOf( 'linux' ) != -1 )){
        this.sisop = 'linux';
        }
        }
        var is = new Is();
        function enterSubmit(keypressEvent,submitFunc){
        var kCode = (is.ie) ? keypressEvent.keyCode : keypressEvent.which
        if( kCode == 13) eval(submitFunc);
        }
        var W = screen.width;
        var H = screen.height;
        var FONTSIZE = 0;
        switch (W){
        case 640:
        FONTSIZE = 8;
        break;
        case 800:
        FONTSIZE = 10;
        break;
        case 1024:
        FONTSIZE = 12;
        break;
        default:
        FONTSIZE = 14;
        break;
        }
        ";
    echo replace_double(" ",str_replace(chr(13),"",str_replace(chr(10),"","
        document.writeln('
        <style>
        body {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Bg'].";
        }
        table {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        cursor: default;
        }
        select {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Link'].";
        }
        input {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Link'].";
        }
        textarea {
        font-family : Courier;
        font-size: 12px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Link'].";
        }
        A {
        font-family : Arial;
        font-size : '+FONTSIZE+'px;
        font-weight : bold;
        text-decoration: none;
        color: ".$fm_color['Text'].";
        }
        A:link {
        color: ".$fm_color['Text'].";
        }
        A:visited {
        color: ".$fm_color['Text'].";
        }
        A:hover {
        color: ".$fm_color['Link'].";
        }
        A:active {
        color: ".$fm_color['Text'].";
        }
        </style>
        ');
        ")));
