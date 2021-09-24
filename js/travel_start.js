let strings = [{
        name: 'system',
        content: "",
    },
    {
        name: '',
        content: "【 提示 】 你已踏入迷宮",
    },
];

const type = new Type(strings, 30)
type.typingEffect();

function behavior(behavior_type) {
    $.ajax({
        type: "post",
        url: "./home.php",
        data: {
            type: "start_role_talent",
            role_id: document.getElementById("role_id").value,
            behavior_type: behavior_type,
            difficulty: document.getElementById("difficulty").value,
        },
        datatype: "json",
        success: function(result) {
            result = JSON.parse(result);
            const talent = new Type(result, 30);
            talent.typingEffect();
        },
    });
}