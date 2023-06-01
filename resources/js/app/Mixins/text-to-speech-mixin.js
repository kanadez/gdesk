
let TextToSpeechMixin = {
    data: function () {
        return {
            playingNow: false,
            paused: false,
        }
    },
    computed: {
        isTextPlaying() {
            return this.playingNow;
        },
        isTextPaused() {
            return this.paused;
        },
    },
    created: function () {
    },
    methods: {
        playText(text) {
            var msg = new SpeechSynthesisUtterance();
            var voices = speechSynthesis.getVoices();
            msg.voice = voices[3];
            msg.text = text;
            speechSynthesis.speak(msg);
            this.playingNow = true;
        },

        pauseText() {
            speechSynthesis.pause();
            this.playingNow = false;
        },
    }
};

export {TextToSpeechMixin};
