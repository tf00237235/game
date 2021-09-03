var i = 0;
var speed = 50; /* The speed/duration of the effect in milliseconds */

function typeWriter(text_name, text_content) {

    var txt = text_name + "：" + text_content;
    if (i < txt.length) {
        document.getElementById("main").innerHTML += txt.charAt(i);
        i++;
        setTimeout(typeWriter, speed);
    }
}