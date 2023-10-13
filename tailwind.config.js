/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.{vue,js,blade.php}"],
    theme: {
        extend: {
            backgroundImage: {
                mesh: "radial-gradient(at 0% 6%, hsla(243,66%,68%,1) 0px, transparent 50%), radial-gradient(at 24% 33%, hsla(114,67%,77%,1) 0px, transparent 50%), radial-gradient(at 90% 85%, hsla(275,66%,60%,1) 0px, transparent 50%), radial-gradient(at 11% 29%, hsla(26,64%,70%,1) 0px, transparent 50%), radial-gradient(at 3% 29%, hsla(78,78%,60%,1) 0px, transparent 50%), radial-gradient(at 16% 72%, hsla(6,93%,77%,1) 0px, transparent 50%), radial-gradient(at 19% 34%, hsla(200,70%,79%,1) 0px, transparent 50%);",
            },
            backgroundColor: {
                meshFallback: "#ee99ff",
            },
            borderColor: {
                primary: "#a9a9a95b",
                focus: "#ee99ff",
            },
            colors: {
                link: "#fa208d",
            },
        },
    },
    plugins: [],
};
