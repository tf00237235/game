function submit_creat(form) {

    if (document.getElementById("role").value == '深淵') {
        document.getElementById("dialog").innerHTML = "如果不打算遊玩這個遊戲，請直接關閉網頁就好...<br>真的，不勉強！";
        $("#dialog").dialog({
            buttons: {
                "Ok": function() {
                    $(this).dialog("close");
                }
            },
            modal: true,
            closeOnEscape: false,
            dialogClass: "dlg-no-close",
            title: "你完全沒有動關於角色的骰子欸！",
        });
    } else if (document.getElementById("name").value == '') {
        document.getElementById("dialog").innerHTML = "不好意思，請你取個名稱好嗎？";
        $("#dialog").dialog({
            buttons: {
                "Ok": function() {
                    $(this).dialog("close");
                }
            },
            modal: true,
            closeOnEscape: false,
            dialogClass: "dlg-no-close",
            title: "請讓這個世界認識你！",
        });
    } else {
        form.type.value = 'creatRole';
        form.method = 'post';
        form.action = "./home.php"
        form.submit();
    }
}