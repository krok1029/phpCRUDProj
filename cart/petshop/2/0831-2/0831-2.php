<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .box {
            width: 600px;
            height: 1200px;
            background-color: #9fcdff;
        }

        #info {
            position: fixed;
            top: 0px;
            left: 50%;
        }
    </style>
</head>

<body>
    <div id="info">
        <a href="javascript: pState({data:{name:'bill'}, url:'/abc/def'})">/abc/def</a><br>
        <a href="javascript: pState({data:{name:'david'}, url:'/test.html'})">bbb</a><br>
        <a href="javascript: pState({data:{name:'flora'}, url:'/aaaa/bbb/cc.html'})">ccc</a><br>
    </div>
    <div id="aaa" class="box">aaa</div>

    <script>
        function pState(obj) {
            history.pushState(obj.data, '', obj.url);
        }

        window.addEventListener('popstate', function(event) {
            console.log(event.state);
        });
    </script>

</body>

</html>