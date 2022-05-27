<!DOCTYPE html>
<html>
<style>
    #container {
        width: 100%;
        height: auto;
        position: relative;
    }
    #animate {
        width: 100px;
        height: 100px;
        margin-top: 50px;
        position: absolute;
        background-color: blue;
        transition: all .5s ease-in-out;
    }

</style>

<body>
    <div id="container">
        <div id="animate"></div>
    </div>
    <script>
        window.onload=()=>{
            var elem = document.getElementById("animate");
            var top_pos = 0;
            var left_pos = 0;
            var status = '';
            setInterval(frame, 1000);

            function frame() {
                var bounding = elem.getBoundingClientRect();
                if (status == 'bottom-out') {
                    top_pos -= 10;
                    left_pos += 10;
                }else if (status == 'left-out') {
                    top_pos -= 10;
                    left_pos += 10;
                }else if (status == 'top-out') {
                    top_pos += 10;
                    left_pos -= 10;
                    console.log('left pos ',left_pos);
                }else if (status == 'right-out') {
                    top_pos -= 10;
                    left_pos -= 10;
                }else{
                    top_pos += 10;
                    left_pos += 10;
                }
                if (bounding.top <= 0) {
                    // Top is out of viewport
                    status = 'top-out';
                    elem.style.top = top_pos + "px";
                    elem.style.left = left_pos + "px";
                }else if (bounding.left <= 0) {
                    // Left side is out of viewoprt
                    status = 'left-out';
                    elem.style.top = top_pos + "px";
                    elem.style.left = left_pos + "px";
                }else if (bounding.bottom >= (window.innerHeight || document.documentElement.clientHeight)) {
                    // Bottom is out of viewport
                    status = 'bottom-out';
                    elem.style.top = top_pos + "px";
                    elem.style.left = left_pos + "px";
                }else if (bounding.right >= (window.innerWidth || document.documentElement.clientWidth)) {
                    // Right side is out of viewport
                    status = 'right-out';
                    elem.style.top = top_pos + "px";
                    elem.style.left = left_pos + "px";
                }else {
                    elem.style.top = top_pos + "px";
                    elem.style.left = left_pos + "px";
                }
            }
        }
    </script>
</body>
</html>
