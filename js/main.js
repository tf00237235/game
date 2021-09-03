let isStop = false;

function scroll() {
    var elem = document.querySelector('.board');
    elem.scrollTop = elem.scrollHeight;
}

function dq(selector) {
    return document.querySelector(selector);
}

class Type {
    constructor(strings) {
        this.strings = strings
        this.countStr = 0;
        this.countChar = 0;
        this.timer = 0;
        this.speed = 100;
    }
    typingEffect() {
        if (this.countChar < this.strings[this.countStr].content.length) {
            dq('.board__content:last-child').innerHTML += this.strings[this.countStr].content.charAt(this.countChar);
            this.countChar++;
            this.timer = setTimeout(this.typingEffect.bind(this), this.speed);
        } else if (this.countStr < this.strings.length - 1) {
            this.countStr++;
            this.countChar = 0;
            const item = document.createElement('div');
            item.className = 'board__content';
            this.switchStyle(item);
            dq('.board').append(item);
            this.typingEffect();
            scroll();
        }
    }

    switchStyle(item) {
        switch (strings[this.countStr].name) {
            case 'other':
                item.classList.add('name--text');
                break;
            case 'system':
                item.classList.add('system--text');
                break;
            default:
                break;
        }
    }
}