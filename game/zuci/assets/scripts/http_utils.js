var httpUtils = cc.Class({
    extends: cc.Component,

    properties: {
        // foo: {
        //    default: null,      // The default value will be used only when the component attaching
        //                           to a node for the first time
        //    url: cc.Texture2D,  // optional, default is typeof default
        //    serializable: true, // optional, default is true
        //    visible: true,      // optional, default is true
        //    displayName: 'Foo', // optional
        //    readonly: false,    // optional, default is false
        // },
        // ...
    },

    statics: {
        instance: null
    },

    // use this for initialization
    onLoad: function () {},

    httpGets: function (url, callback) {
        // var xhr = cc.loader.getXMLHttpRequest();
        // xhr.onreadystatechange = function () {
        //     if ( xhr.readyState === 4 && (xhr.status >= 200 && xhr.status < 300)) {
        //         var respone = xhr.responseText;
        //         callback(respone);
        //     }
        // };
        // xhr.open("GET", url, true);
        // if (cc.sys.isNative) {
        //     xhr.setRequestHeader("Accept-Encoding","gzip,deflate","text/html;charset=UTF-8");
        // }

        // // note: In Internet Explorer, the timeout property may be set only after calling the open()
        // // method and before calling the send() method.
        // xhr.timeout = 5000; // 5 seconds for timeout

        // xhr.send();


        ////////////

        var request = cc.loader.getXMLHttpRequest();
        request.open("GET", url, true);
        request.onreadystatechange = function () {
            if (request.readyState == 4 && (request.status >= 200 && request.status <= 207)) {
                var httpStatus = request.statusText;
                var response = request.responseText;
                console.log("Status: Got GET response! " + httpStatus);
                callback(true, response);
            } else {
                callback(false, request);
            }
        };
        request.send();

    },

    httpPost: function (url, params, callback) {
        /*
        var xhr = cc.loader.getXMLHttpRequest();
        xhr.onreadystatechange = function () {
            cc.log('xhr.readyState=' + xhr.readyState + '  xhr.status=' + xhr.status);
            if (xhr.readyState === 4 && (xhr.status >= 200 && xhr.status < 300)) {
                var respone = xhr.responseText;
                callback(respone);
            } else {
                callback(-1);
            }
        };
        xhr.open("POST", url, true);
        if (cc.sys.isNative) {
            xhr.setRequestHeader("Accept-Encoding", "gzip,deflate");
        }

        // note: In Internet Explorer, the timeout property may be set only after calling the open()
        // method and before calling the send() method.
        xhr.timeout = 5000; // 5 seconds for timeout

        xhr.send(params);
        */
       
       var xhr = cc.loader.getXMLHttpRequest();
       xhr.open("POST", url, true);
       xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
       xhr.onreadystatechange = function () {
           if (xhr.readyState == 4 && (xhr.status >= 200 && xhr.status <= 207)) {
               err = false;
           } else {
               err = true;
           }
           var response = xhr.responseText;
           callback(err, response);
       };
       xhr.send(params);
    }
});

httpUtils.getInstance = function () {
    if (httpUtils.instance == null) {
        httpUtils.instance = new httpUtils();
    }
    return httpUtils.instance;
};