$.ajax({
    type: "post",
    url: "./home.php",
    data: {
        type: "start_role_talent",
        role_id: document.getElementById("role_id").value,
        difficulty: document.getElementById("difficulty").value,
    },
    datatype: "json",
    success: function(result) {
        result = JSON.parse(result);
        const talent = new Type(result, 30);
        talent.typingEffect();
    },

});

function change_footer() {
    document.getElementById("footer_status").href = "javascript:show_status();";
    document.getElementById("footer_equipment").href = "javascript:show_equipment();";
    document.getElementById("footer_talent").href = "javascript:show_talent();";
    document.getElementById("footer_bag").href = "javascript:show_bag();";
    document.getElementById("footer_travel").href = "javascript:show_travel();";
}