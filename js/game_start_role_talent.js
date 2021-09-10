$.ajax({
    type: "post",
    url: "./home.php",
    data: {
        type: "start_role_talent",
        role_id: document.getElementById("role_id").value,
    },
    datatype: "json",
    success: function(result) {
        console.log(result);
        result = JSON.parse(result);
        console.log(result);
        result.unshift({
            name: 'system',
            content: "[系統] 初始化中，請稍後！"
        });
        const talent = new Type(result, 100);
        talent.typingEffect();
    },
});