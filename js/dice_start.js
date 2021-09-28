function dice_start() {
    var dice = Math.floor(Math.random() * 100);;
    var num = document.getElementById("start_num").innerHTML;
    var num_trap = document.getElementById("start_trap").innerHTML;
    if (Number(num_trap)) {
        document.getElementById("trap").innerHTML = "再連點啊！";
    }
    if (dice <= 90) {
        var msg = "你骰到：「" + dice + "」還不足以稱為天選之人(第" + num + "次)";
        document.getElementById("start_num").innerHTML = Number(num) + 1;
        document.getElementById("real_start").style.display = "none";
        document.getElementById("inline_content").innerHTML = "";
    } else {
        msg = "尊敬的天選之人啊！請正式進入此遊戲(第" + num + "次)";
        //document.getElementById("inline_content").innerHTML = "";
        document.getElementById("real_start").style.display = "block";
        document.getElementById("trap").innerHTML = "";
        document.getElementById("start_num").innerHTML = 1;
        document.getElementById("start_trap").innerHTML = 1;
    }
    switch (num) {
        case "20":
            document.getElementById("trap").innerHTML = "20次了喔！";
            break;
        case "40":
            document.getElementById("trap").innerHTML = "你行不行啊？";
            break;
        case "60":
            document.getElementById("trap").innerHTML = "......";
            break;
        case "80":
            document.getElementById("trap").innerHTML = "好了啦，不用勉強自己。";
            break;
        case "100":
            document.getElementById("trap").innerHTML = ".....請你截圖並連繫我！";
            break;
        case "120":
            document.getElementById("trap").innerHTML = "你進去吧，我不忍心看你繼續點了...";
            document.getElementById("real_start").style.display = "block";
            break;
    }
    document.getElementById("start").innerHTML = msg;
}

function hidden_real_start() {
    document.getElementById("real_start").style.display = "none";
    document.getElementById("start").innerHTML = '';
    document.getElementById("start_num").innerHTML = 1;
}