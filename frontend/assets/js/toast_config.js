var options = {
    // STRING: main class name used to styling each toast message with CSS:
    // .... IMPORTANT NOTE:
    // .... if you change this, the configuration consider that you´re
    // .... re-stylized the plug-in and default toast styles, including CSS3 transitions are lost.
    classname: "toast",
    // STRING: name of the CSS transition that will be used to show and hide all toast by default:
    transition: "fade",
    // BOOLEAN: specifies the way in which the toasts will be inserted in the HTML code:
    // .... Set to BOOLEAN TRUE and the toast messages will be inserted before those already generated toasts.
    // .... Set to BOOLEAN FALSE otherwise.
    insertBefore: true,
    // INTEGER: duration that the toast will be displayed in milliseconds:
    // .... Default value is set to 4000 (4 seconds).
    // .... If it set to 0, the duration for each toast is calculated by text-message length.
    duration: 4000,
    // BOOLEAN: enable or disable toast sounds:
    // .... Set to BOOLEAN TRUE  - to enable toast sounds.
    // .... Set to BOOLEAN FALSE - otherwise.
    // NOTE: this is not supported by mobile devices.
    enableSounds: false,
    // BOOLEAN: enable or disable auto hiding on toast messages:
    // .... Set to BOOLEAN TRUE  - to enable auto hiding.
    // .... Set to BOOLEAN FALSE - disable auto hiding. Instead the user must click on toast message to close it.
    autoClose: true,
    // BOOLEAN: enable or disable the progressbar:
    // .... Set to BOOLEAN TRUE  - enable the progressbar only if the autoClose option value is set to BOOLEAN TRUE.
    // .... Set to BOOLEAN FALSE - disable the progressbar.
    progressBar: false,
    // IMPORTANT: mobile browsers does not support this feature!
    // Yep, support custom sounds for each toast message when are shown if the
    // enableSounds option value is set to BOOLEAN TRUE:
    // NOTE: the paths must point from the project's root folder.
    sounds: {
        // path to sound for informational message:
        info: "/dist/sounds/info/1.mp3",
        // path to sound for successfull message:
        success: "/dist/sounds/success/1.mp3",
        // path to sound for warn message:
        warning: "/dist/sounds/warning/1.mp3",
        // path to sound for error message:
        error: "/dist/sounds/error/1.mp3",
    },

    // callback:
    // onShow function will be fired when a toast message appears.
    onShow: function (type) {},

    // callback:
    // onHide function will be fired when a toast message disappears.
    onHide: function (type) {},

    // the placement where prepend the toast container:
    prependTo: document.body.childNodes[0]
};

// more js code...
