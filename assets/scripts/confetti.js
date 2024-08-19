setTimeout(function() {
    confetti({
        angle: 135,
        particleCount: 100,
        spread: 70,
        origin: { y: 1, x: 1 }
    });
    confetti({
        angle: 45,
        particleCount: 100,
        spread: 70,
        origin: { y: 1, x: 0 }
    });
}, 100);