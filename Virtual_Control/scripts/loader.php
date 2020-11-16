<?php

class loader {

    function button($id, $caption, $disabled, $icon = '', $color = 'dark') {
        $d_text = '';
        if ($disabled) {
            $d_text = 'disabled';
        }
        return "<button type=\"button\" class=\"btn btn-" . $color . " btn-block mb-2 btn-lg shadow-lg\" id=\"$id\" $d_text><i class=\"$icon\"></i>$caption</button>";
    }

    function button_s($id, $caption, $disabled, $icon = '', $color = 'dark') {
        $d_text = '';
        if ($disabled) {
            $d_text = 'disabled';
        }
        return "<button type=\"button\" class=\"btn btn-" . $color . "-smart btn-block mb-2 btn-lg shadow-lg\" id=\"$id\" $d_text><i class=\"$icon\"></i>$caption</button>";
    }

    function Title($title, $icon) {
        return "<div class=\"py-2 bg-dark\"><div class=\"container\"><div class=\"col-md-12 py-2\"><h2><i class=\"$icon\"></i>$title</h2></div></div></div>";
    }

    function SubTitle($title, $caption, $icon) {
        return "<div class=\"col-md-12 py-2\"><h3><i class=\"$icon\"></i>$title</h3><hr class=\"orange\"><p class=\"py-2\">$caption</p></div>";
    }

    function loadHeader($site_title, $title, $ishideC = false) {
        $hide_text = '';
        if ($ishideC) { $hide_text = '.'; }
        return '<meta charset="utf-8">
        <meta name="application-name" content="' . $site_title . '">
        <link rel="icon" href="' . $hide_text . './images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . $title . ' - ' . $site_title . '</title>
        <meta name="description" content="' . $site_title . '">
        <link rel="stylesheet" href="' . $hide_text . './style/awesome.min.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './font-awesome/css/all.min.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './font-awesome/css/brands.min.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './font-awesome/css/regular.min.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './font-awesome/css/fontawesome.min.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './font-awesome/css/solid.min.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './style/aquamarine.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './style/dialog.css" type="text/css">
        <link rel="stylesheet" href="' . $hide_text . './style/Roboto.css" type="text/css">
        <script src="' . $hide_text . './js/animate-in.js"></script>
        <script src="' . $hide_text . './js/loader.js"></script>';
    }

    function load_Logo() {
        return '<div class="bg-primary pt-5">
            <div class="container">
            <div class="row mg-2">
            <div class="col-md-12 m-2">
            <svg width="100%" height="150px" version="1.1" viewBox="0 0 167.32 46.199" xmlns="http://www.w3.org/2000/svg" fill="#000000">
            <g transform="translate(-17.105 -89.122)">
            <path transform="matrix(.26458 0 0 .26458 17.105 89.122)" d="m44.221 0.0019531v68.324h-32.475v-68.295c-7.3531 9.0929-11.731 23.781-11.746 39.449-0.0014173 26.815 12.532 48.555 27.984 48.553 15.453 0.00223 27.97-21.738 27.969-48.553-0.010204-15.677-4.376-30.381-11.732-39.479zm297.72 26.609v56.482h11.773v-56.482h-11.773zm278.68 0v56.482h11.775v-56.482h-11.775zm-444.1 0.18945c-1.987 0-3.6122 0.55813-4.8789 1.6758-1.2668 1.1177-1.9004 2.5607-1.9004 4.3242 0 1.7138 0.63363 3.1633 1.9004 4.3555 1.2666 1.1673 2.8919 1.752 4.8789 1.752 2.0119 0 3.6409-0.56848 4.8828-1.7109 1.2668-1.1425 1.9004-2.6082 1.9004-4.3965 0-1.7635-0.63363-3.2065-1.9004-4.3242-1.2418-1.1177-2.871-1.6758-4.8828-1.6758zm62.93 6.5586-11.738 3.3496v8.2363h-6.1855v8.6797h6.1855v17.477c0 8.6186 4.1476 12.928 12.443 12.928 3.5021 0 6.1226-0.46378 7.8613-1.3828v-8.7148c-1.3163 0.7203-2.5941 1.0781-3.8359 1.0781-3.1544 0-4.7305-1.9871-4.7305-5.9609v-15.424h8.5664v-8.6777h-8.5664v-11.588zm289.25 0-11.732 3.3496v8.2363h-6.1856v8.6797h6.1856v17.477c0 8.6186 4.1478 12.928 12.443 12.928 3.5022 0 6.1226-0.46378 7.8613-1.3828v-8.7148c-1.3165 0.7203-2.5941 1.0781-3.8359 1.0781-3.1545 0-4.7363-1.9871-4.7363-5.9609v-15.424h8.5723v-8.6777h-8.5723v-11.588zm-124.84 10.65v0.001953c-6.5072 0-11.712 1.8745-15.611 5.625-3.8747 3.7256-5.8125 8.8322-5.8125 15.314 0 5.6133 1.8112 10.195 5.4375 13.746 3.6263 3.5517 8.3711 5.3301 14.232 5.3301 5.0172 0 8.858-0.77253 11.516-2.3125v-9.4609c-2.8066 1.838-5.6785 2.7539-8.6094 2.7539-3.3034 0-5.8974-0.95272-7.7852-2.8652-1.8877-1.9373-2.8301-4.5968-2.8301-7.9746 0-3.4773 0.98115-6.1977 2.9434-8.1602 1.987-1.987 4.6649-2.9785 8.043-2.9785 3.0302 0 5.7792 0.91795 8.2383 2.7559v-9.9863c-2.0118-1.1923-5.2664-1.7891-9.7617-1.7891zm36.211 0v0.001953c-6.4329 0-11.535 1.7886-15.311 5.3652-3.7753 3.5518-5.666 8.4841-5.666 14.793 0 6.11 1.8151 10.951 5.4414 14.527 3.6511 3.5517 8.6588 5.3301 15.018 5.3301 6.4579 0 11.533-1.8377 15.234-5.5137 3.7257-3.676 5.5898-8.6818 5.5898-15.016 0-5.8617-1.801-10.57-5.4023-14.121-3.6014-3.5766-8.5711-5.3672-14.904-5.3672zm52.121 0v0.001953c-5.3649 0-9.4493 2.3223-12.256 6.9668h-0.14844v-6.0332h-11.773v38.146h11.773v-21.754c0-2.4092 0.65651-4.3964 1.9726-5.9609 1.3166-1.5648 2.9926-2.3477 5.0293-2.3477 4.2224 0 6.332 2.9557 6.332 8.8672v21.197h11.738v-23.361c0-10.481-4.2231-15.723-12.668-15.723zm100.48 0v0.001953c-6.4329 0-11.535 1.7886-15.311 5.3652-3.7755 3.5518-5.666 8.4841-5.666 14.793 0 6.11 1.817 10.951 5.4434 14.527 3.6511 3.5517 8.6516 5.3301 15.01 5.3301 6.4579 0 11.54-1.8377 15.24-5.5137 3.7257-3.676 5.5898-8.6818 5.5898-15.016 0-5.8617-1.8007-10.57-5.4023-14.121-3.6014-3.5766-8.5711-5.3672-14.904-5.3672zm-276.44 0.001953c-2.3337 4.15e-4 -4.9304 0.32347-7.7852 0.96875-2.8315 0.64573-5.068 1.3919-6.707 2.2363v8.8672c4.0982-2.7073 8.4215-4.0605 12.967-4.0605 4.5205 0 6.7793 2.0849 6.7793 6.2578l-10.355 1.3809c-8.7676 1.1425-13.152 5.415-13.152 12.816 0 3.5021 1.0548 6.3071 3.166 8.418 2.136 2.0864 5.0553 3.1309 8.7559 3.1309 5.0172 0 8.8029-2.1359 11.361-6.4082h0.15235v5.4746h11.139v-22.801c0-10.852-5.4404-16.28-16.316-16.281h-0.0039zm-101.93 0.26367c-4.8433 0-8.1978 2.5835-10.061 7.75h-0.14844v-7.0801h-11.773v38.148h11.773v-18.219c0-3.2289 0.71958-5.774 2.1602-7.6367 1.4407-1.8876 3.4418-2.8301 6-2.8301 1.8876 0 3.5393 0.38234 4.9551 1.1523v-10.838c-0.69548-0.2982-1.6643-0.44726-2.9062-0.44726zm351.55 0c-4.8432 0-8.1939 2.5835-10.057 7.75h-0.15234v-7.0801h-11.773v38.148h11.773v-18.219c0-3.2289 0.71921-5.774 2.1602-7.6367 1.4404-1.8876 3.4418-2.8301 6-2.8301 1.8878 0 3.5393 0.38234 4.9551 1.1523v-10.838c-0.69482-0.2982-1.6651-0.44726-2.9062-0.44726zm-441.52 0.66992 13.521 38.148h13.41l14.197-38.148h-12.297l-6.7793 23.73c-0.74522 2.608-1.2031 4.7933-1.377 6.5566h-0.15234c-0.12389-1.8628-0.55562-4.1216-1.3008-6.7793l-6.6309-23.508h-12.592zm46.23 0v38.148h11.775v-38.148h-11.775zm83.457 0v23.025c0 10.705 4.4254 16.057 13.268 16.057 4.8682 0 8.7505-2.2464 11.656-6.7422h0.1875v5.8086h11.738v-38.148h-11.738v21.832c0 2.5334-0.63374 4.5451-1.9004 6.0352-1.2668 1.4654-2.9552 2.1953-5.0664 2.1953-4.272 0-6.4082-2.694-6.4082-8.084v-21.979h-11.736zm185.84 8.0859c5.688 0 8.5312 3.5631 8.5312 10.691 0 7.5258-2.8168 11.285-8.4551 11.285-5.9113 0-8.8672-3.6594-8.8672-10.986 0-3.502 0.77066-6.2124 2.3106-8.125 1.5399-1.9125 3.6988-2.8652 6.4805-2.8652zm152.61 0c5.6879 0 8.5312 3.5631 8.5312 10.691 0 7.5258-2.8167 11.285-8.4551 11.285-5.9114 0-8.8672-3.6594-8.8672-10.986 0-3.502 0.76682-6.2124 2.3066-8.125 1.5393-1.9125 3.7024-2.8652 6.4844-2.8652zm-510.49 1.6543c-15.453-0.001436-27.97 12.516-27.969 27.969-0.001123 15.453 12.516 27.986 27.969 27.984 9.0336-0.01134 17.513-4.3877 22.756-11.742h-37.938l16.145-32.479h21.775c-5.2396-7.3537-13.709-11.718-22.738-11.732zm239.52 10.342v2.5684c0 2.3347-0.69335 4.2726-2.084 5.8125-1.3909 1.515-3.1898 2.2715-5.4004 2.2715-1.5895 0-2.8569-0.42109-3.8008-1.2656-0.91908-0.86933-1.3828-1.9712-1.3828-3.3125 0-2.9557 1.9171-4.6868 5.7422-5.1836l6.9258-0.89062zm-302.05 5.8359c2.0896 9.07e-4 3.7828 1.6931 3.7891 3.7832-9.42e-4 2.0933-1.6958 3.792-3.7891 3.793-2.0933-9.07e-4 -3.7901-1.699-3.791-3.793 0.006048-2.0896 1.7015-3.7823 3.791-3.7832zm17.082 0c2.0934-0.003779 3.7945 1.6895 3.8008 3.7832-9.38e-4 2.0971-1.7026 3.7974-3.8008 3.793-2.0934-9.07e-4 -3.7901-1.699-3.791-3.793 0.006047-2.0896 1.7013-3.7823 3.791-3.7832zm30.662 0.47461c2.748-0.00378 4.976 2.2248 4.9766 4.9727-0.005669 2.7442-2.2324 4.9638-4.9766 4.959-2.7368-0.007559-4.9533-2.2221-4.959-4.959 6.16e-4 -2.7406 2.2186-4.9668 4.959-4.9727zm29.205 49.629c-14.859 0-26.824 11.965-26.824 26.824 0 14.859 11.965 26.818 26.824 26.818h439.6c14.859 0 26.824-11.959 26.824-26.818 0-14.859-11.965-26.824-26.824-26.824h-439.6zm162.85 12.59c0.34439 0 0.66733 0.06908 0.96093 0.19531 0.29375 0.1217 0.54899 0.29279 0.76172 0.51563 0.22284 0.22261 0.39409 0.48329 0.51563 0.77734 0.12151 0.29405 0.17773 0.60437 0.17773 0.9375 0 0.33411-0.05596 0.65123-0.17773 0.94531-0.12151 0.29405-0.29277 0.55454-0.51563 0.77735-0.2126 0.21241-0.468 0.38548-0.76172 0.51758-0.29374 0.1217-0.61667 0.17773-0.96093 0.17773-0.34439 0-0.66298-0.05717-0.9668-0.17773-0.29374-0.13229-0.5545-0.30415-0.77734-0.51758-0.21275-0.22262-0.37848-0.4833-0.5-0.77735-0.12152-0.29404-0.1836-0.61101-0.1836-0.94531 0-0.33411 0.06159-0.64458 0.1836-0.9375 0.12151-0.29405 0.28728-0.55446 0.5-0.77734 0.22284-0.22262 0.48362-0.39385 0.77734-0.51563 0.30384-0.13228 0.62243-0.19531 0.9668-0.19531zm-40.311 0.62305h8.1562v18.58h4.4512v2.8848h-13.143v-2.8848h4.8926v-15.695h-4.3574v-2.8848zm17.131 0h8.1621v18.58h4.4453v2.8848h-13.135v-2.8848h4.8906v-15.695h-4.3633v-2.8848zm188.02 0h3.7031v12.561l5.3965-6.5293h4.6289l-6.2559 7.0332 6.7793 8.4004h-4.8496l-5.6992-7.7168v7.7168h-3.7031v-21.465zm103.28 0h8.1621v18.58h4.4453v2.8848h-13.137v-2.8848h4.8926v-15.695h-4.3633v-2.8848zm-353.73 1.0918v4.9395h6v2.8848h-6v7.2598c0 0.88101 0.20621 1.5501 0.61132 2.0059 0.40503 0.44568 1.0833 0.67187 2.0352 0.67187 0.60765 0 1.1982-0.05792 1.7754-0.15429 0.57721-0.10356 1.1021-0.21733 1.5781-0.34961v2.9609c-0.66842 0.15156-1.339 0.27611-2.0176 0.36719-0.67852 0.09184-1.3221 0.13672-1.9297 0.13672-1.0127 0-1.8896-0.10337-2.6289-0.32031-0.72917-0.21241-1.3361-0.53729-1.8223-0.97266-0.47601-0.43532-0.82955-0.98807-1.0625-1.6562-0.22273-0.67888-0.33203-1.4844-0.33203-2.416v-7.5312h-4.1504v-2.8867h4.1504v-3.9512l3.793-0.98829zm188.49 0v4.9395h5.9941v2.8848h-5.9941v7.2598c0 0.88101 0.20034 1.5501 0.60547 2.0059 0.40505 0.44568 1.0833 0.67187 2.0352 0.67187 0.60768 0 1.1982-0.05792 1.7754-0.15429 0.57721-0.10356 1.1021-0.21733 1.5781-0.34961v2.9609c-0.66841 0.15156-1.3391 0.27611-2.0176 0.36719-0.67854 0.09184-1.3221 0.13672-1.9297 0.13672-1.0127 0-1.8896-0.10337-2.6289-0.32031-0.72919-0.21241-1.3362-0.53729-1.8223-0.97266-0.47603-0.43532-0.82956-0.98807-1.0625-1.6562-0.22261-0.67888-0.33398-1.4844-0.33398-2.416v-7.5312h-4.1484v-2.8867h4.1484v-3.9512l3.8008-0.98829zm-238.34 0.24414c0.45567 0 0.88043 0.01053 1.2754 0.03321 0.40514 0.02268 0.79674 0.06868 1.1816 0.125 0.3848 0.04611 0.77499 0.11714 1.1699 0.20898 0.39491 0.09185 0.80315 0.20712 1.2285 0.34961v3.7109c-0.86081-0.40551-1.6792-0.69601-2.459-0.86719-0.77972-0.17234-1.487-0.25976-2.125-0.25976-0.9418 0-1.7476 0.17104-2.416 0.51562-0.66841 0.33411-1.2208 0.81433-1.6562 1.4316-0.42536 0.60764-0.74074 1.3329-0.94336 2.1836-0.20251 0.84079-0.30274 1.7725-0.30274 2.7852 4e-5 1.0728 0.09989 2.0341 0.30274 2.8848 0.21275 0.84083 0.53915 1.5553 0.9746 2.1426 0.43547 0.58699 0.98944 1.0397 1.668 1.3535 0.67852 0.3035 1.4843 0.45117 2.416 0.45117 0.33415 0 0.69527-0.02263 1.0801-0.09179 0.39491-0.06879 0.79257-0.16081 1.1875-0.26172 0.40514-0.1149 0.79481-0.23642 1.1797-0.37891 0.39502-0.15156 0.75948-0.29833 1.0937-0.45117v3.4902c-0.88103 0.3549-1.734 0.61676-2.5644 0.78907-0.82027 0.17234-1.6733 0.25976-2.5644 0.25976-1.4279 0-2.695-0.20722-3.7988-0.62304-1.0938-0.42501-2.0256-1.0522-2.7852-1.8828-0.74939-0.83047-1.3199-1.8605-1.7148-3.0859-0.3848-1.2355-0.57617-2.6663-0.57617-4.2969 0-1.6709 0.20939-3.1568 0.63476-4.4531 0.42536-1.3064 1.0383-2.4018 1.8281-3.293 0.78994-0.90169 1.7431-1.5889 2.8672-2.0547 1.1342-0.47554 2.4107-0.71289 3.8184-0.71289v-0.00196zm-38.727 0.27344h5.2285l5.877 19.855h-4.1309l-1.0918-3.918h-6.9277l-1.1211 3.918h-3.7109l5.877-19.855zm235.38 0h4.5898l5.4238 11.926 0.95703 2.3203v-8.7617-5.4844h3.4004v19.855h-4.5527l-5.3184-11.848-1.0918-2.5527v8.2324 6.168h-3.4082v-19.855zm153.99 0h14.703v3.1152h-5.4375v16.74h-3.8281v-16.738h-5.4375v-3.1172zm-386.9 3.6445-2.6113 9.2363h5.1934l-2.582-9.2363zm51.709 0.43946c1.1545 0 2.1846 0.17104 3.0859 0.51562 0.90124 0.33411 1.6632 0.83975 2.291 1.5078 0.62786 0.66853 1.1076 1.5017 1.4316 2.4941 0.33415 0.98212 0.49805 2.1158 0.49805 3.4121 0 1.2152-0.17321 2.329-0.51758 3.3418-0.34439 1.0028-0.8401 1.863-1.4883 2.582-0.64808 0.71906-1.439 1.2788-2.3809 1.6738-0.94179 0.39515-2.0175 0.5957-3.2227 0.5957-1.1443 0-2.169-0.17129-3.0703-0.50586-0.90125-0.33411-1.6631-0.83195-2.291-1.4902-0.62787-0.66852-1.1072-1.4939-1.4414-2.4863-0.33415-1.0028-0.50586-2.1695-0.50586-3.4961 0-1.2254 0.17322-2.3392 0.51758-3.3418 0.35448-1.0028 0.85386-1.8556 1.502-2.5644 0.6583-0.70874 1.4625-1.2597 2.4043-1.6445 0.9418-0.39515 2.0026-0.59375 3.1875-0.59375zm18.732 0h0.00195c0.7891 2.2e-4 1.4776 0.13342 2.0645 0.39648 0.58743 0.25247 1.0776 0.61509 1.4726 1.0918 0.39492 0.46635 0.68455 1.0364 0.87696 1.7051 0.2025 0.6582 0.30273 1.3926 0.30273 2.2129v10.365h-3.7031v-10.057c0-1.6912-0.62509-2.5391-1.8809-2.5391-0.62787 0-1.2333 0.25253-1.8106 0.75781-0.5671 0.50657-1.1667 1.1955-1.8047 2.0664v9.7695h-3.7109v-15.432h3.2051l0.08985 2.2793c0.31392-0.39511 0.64046-0.75613 0.97461-1.0801 0.34439-0.32391 0.71446-0.59748 1.1094-0.82032 0.39503-0.23319 0.81965-0.41146 1.2754-0.5332 0.45513-0.12155 0.9708-0.18344 1.5371-0.18359zm34.98 0c0.77984 0 1.4689 0.14268 2.0664 0.41601 0.60765 0.26306 1.1145 0.65328 1.5195 1.1797 0.41525 0.52611 0.72165 1.1882 0.91406 1.9883 0.20251 0.7903 0.28628 1.7127 0.25586 2.7559h-3.7227c0.02079-0.57778-0.00817-1.0733-0.08984-1.4785-0.07121-0.40551-0.19499-0.74089-0.36719-1.0039-0.16206-0.26306-0.36242-0.45248-0.60547-0.57422-0.24306-0.1217-0.52396-0.17773-0.83789-0.17773-0.54688 0-1.1099 0.22647-1.6973 0.68164-0.57721 0.44568-1.2192 1.1818-1.918 2.2148v9.7695h-3.7988v-15.434h3.3594l0.13672 2.25c0.25316-0.39515 0.53568-0.75477 0.84961-1.0684 0.32402-0.31332 0.6777-0.57794 1.0625-0.80078 0.39491-0.2332 0.83065-0.41342 1.3066-0.53516 0.47602-0.1217 0.99926-0.18359 1.5664-0.18359zm14.828 0c1.1545 0 2.1807 0.17104 3.082 0.51562 0.90124 0.33411 1.669 0.83975 2.2969 1.5078 0.62787 0.66853 1.0997 1.5017 1.4238 2.4941 0.33415 0.98212 0.50391 2.1158 0.5039 3.4121 0 1.2152-0.17126 2.329-0.51562 3.3418-0.34428 1.0028-0.84208 1.863-1.4902 2.582-0.6482 0.71906-1.4449 1.2788-2.3867 1.6738-0.9418 0.39515-2.0117 0.5957-3.2168 0.5957-1.1443 0-2.167-0.17129-3.0684-0.50586-0.90136-0.33411-1.669-0.83195-2.2969-1.4902-0.62787-0.66852-1.1092-1.4939-1.4434-2.4863-0.33415-1.0028-0.49805-2.1695-0.49805-3.4961 0-1.2254 0.17126-2.3392 0.51562-3.3418 0.35449-1.0028 0.85386-1.8556 1.502-2.5644 0.6583-0.70874 1.4566-1.2597 2.3984-1.6445 0.9418-0.39515 2.0085-0.59375 3.1934-0.59375zm70.133 0h0.00195c0.7891 2.2e-4 1.4776 0.13342 2.0644 0.39648 0.58732 0.25247 1.0777 0.61509 1.4727 1.0918 0.39491 0.46635 0.69236 1.0364 0.88476 1.7051 0.20251 0.6582 0.30274 1.3926 0.30274 2.2129v10.365h-3.7109v-10.057c0-1.6912-0.62509-2.5391-1.8809-2.5391-0.62786 0-1.2274 0.25253-1.8047 0.75781-0.5671 0.50657-1.1726 1.1955-1.8106 2.0664v9.7695h-3.7051v-15.432h3.2051l0.08984 2.2793c0.31393-0.39511 0.64046-0.75613 0.97461-1.0801 0.34439-0.32391 0.71446-0.59748 1.1094-0.82032 0.39491-0.23319 0.82163-0.41146 1.2773-0.5332 0.45513-0.12155 0.96305-0.18344 1.5293-0.18359zm67.053 0c1.0937 0 2.0618 0.17104 2.9024 0.51562 0.84045 0.33411 1.5403 0.80651 2.1074 1.4141 0.56708 0.60764 1.0013 1.3314 1.2949 2.1719 0.29367 0.84083 0.43945 1.7631 0.43945 2.7656 0 0.25248-0.01235 0.58672-0.03125 0.99219-0.01134 0.40547-0.02533 0.77788-0.05859 1.1328h-10.15c0 0.67888 0.09977 1.2765 0.30273 1.793 0.21279 0.51693 0.50811 0.95182 0.88281 1.3066 0.3848 0.3447 0.8386 0.61343 1.3652 0.79493 0.53677 0.18255 1.133 0.27343 1.7812 0.27343 0.74937 0 1.5477-0.05893 2.3984-0.17382 0.86078-0.12171 1.7503-0.31136 2.6719-0.57422v2.9492c-0.39503 0.11489-0.82316 0.21202-1.2891 0.30273-0.46579 0.09222-0.94357 0.17113-1.4297 0.24219-0.48608 0.06878-0.97644 0.11858-1.4726 0.14843-0.49622 0.04611-0.97558 0.07032-1.4414 0.07032-1.1748 0-2.2265-0.1713-3.1582-0.50586-0.9317-0.33411-1.7268-0.82221-2.375-1.4707-0.64819-0.6582-1.1401-1.4656-1.4844-2.4277-0.34431-0.96261-0.51562-2.0759-0.51562-3.3418 0-1.2558 0.17115-2.3971 0.51562-3.4199 0.34432-1.0327 0.82715-1.9193 1.4551-2.6484 0.62785-0.7398 1.3882-1.3023 2.2793-1.6973 0.90126-0.40551 1.9059-0.61133 3.0098-0.61133v-0.00195zm51.299 0c1.1545 0 2.1787 0.17104 3.0801 0.51562 0.90126 0.33411 1.669 0.83975 2.2969 1.5078 0.62786 0.66853 1.1017 1.5017 1.4258 2.4941 0.33411 0.98212 0.50391 2.1158 0.50391 3.4121 0 1.2152-0.17116 2.329-0.51562 3.3418-0.34432 1.0028-0.84209 1.863-1.4902 2.582-0.64823 0.71906-1.4449 1.2788-2.3867 1.6738-0.94178 0.39515-2.0116 0.5957-3.2168 0.5957-1.1444 0-2.167-0.17129-3.0684-0.50586-0.90123-0.33411-1.6709-0.83195-2.2988-1.4902-0.62786-0.66852-1.1072-1.4939-1.4414-2.4863-0.33411-1.0028-0.49805-2.1695-0.49805-3.4961 0-1.2254 0.17116-2.3392 0.51563-3.3418 0.35452-1.0028 0.85388-1.8556 1.502-2.5644 0.65832-0.70874 1.4567-1.2597 2.3984-1.6445 0.94182-0.39515 2.0085-0.59375 3.1934-0.59375zm19.439 0c0.77972 0 1.4748 0.14268 2.0723 0.41601 0.60767 0.26306 1.1086 0.65328 1.5137 1.1797 0.41522 0.52611 0.72168 1.1882 0.91406 1.9883 0.20221 0.7903 0.28648 1.7127 0.25586 2.7559h-3.7226c0.01889-0.57778-0.01161-1.0733-0.08985-1.4785-0.07143-0.40551-0.18898-0.74089-0.36133-1.0039-0.16176-0.26306-0.36838-0.45248-0.61132-0.57422-0.24303-0.1217-0.51822-0.17773-0.83204-0.17773-0.54686 0-1.1158 0.22647-1.7031 0.68164-0.57725 0.44568-1.2134 1.1818-1.9121 2.2148v9.7695h-3.7988v-15.434h3.3535l0.13867 2.25c0.25323-0.39515 0.53959-0.75477 0.85351-1.0684 0.32429-0.31332 0.67767-0.57794 1.0625-0.80078 0.39493-0.2332 0.83061-0.41342 1.3066-0.53516 0.47599-0.1217 0.99346-0.18359 1.5605-0.18359zm32.375 0c0.97225 0 1.8326 0.05596 2.582 0.15234 0.74937 0.10356 1.411 0.21304 1.9883 0.33399v3.0273c-0.88104-0.28347-1.7124-0.48428-2.4922-0.5957-0.76974-0.12171-1.5315-0.1836-2.291-0.1836-0.75949 0-1.374 0.13576-1.8398 0.41016-0.45566 0.27326-0.6836 0.65473-0.6836 1.1406 0 0.2332 0.04639 0.44039 0.13672 0.62305 0.09071 0.18255 0.27034 0.36273 0.53321 0.53516 0.27363 0.17234 0.64705 0.35307 1.123 0.54492 0.48616 0.18255 1.1261 0.38259 1.916 0.60547 0.8911 0.25247 1.6365 0.52431 2.2441 0.81836 0.6076 0.28384 1.0963 0.60853 1.4609 0.97265 0.37455 0.36397 0.64457 0.78264 0.80664 1.248 0.16176 0.46639 0.24414 0.99827 0.24414 1.5957 0 0.88101-0.19864 1.6337-0.59375 2.2617-0.38491 0.61795-0.90068 1.1245-1.5488 1.5195-0.63798 0.3848-1.3711 0.66558-2.1914 0.83789-0.81018 0.18255-1.6356 0.27344-2.4863 0.27344-1.1342 0-2.1569-0.05698-3.0684-0.17187-0.91147-0.10356-1.7719-0.24968-2.582-0.45313v-3.3418c0.95192 0.39512 1.8942 0.68574 2.8359 0.86719 0.95195 0.17235 1.8489 0.25391 2.6895 0.25391 0.97224 0 1.6959-0.1529 2.1719-0.45703 0.48609-0.31333 0.73047-0.7145 0.73047-1.2109 0-0.2332-0.05301-0.44882-0.1543-0.64063-0.10091-0.19313-0.2921-0.37382-0.57617-0.54492-0.27326-0.18255-0.66159-0.36523-1.168-0.54687-0.50634-0.19314-1.168-0.40743-1.9883-0.64063-0.7595-0.21241-1.4306-0.44788-2.0078-0.71094-0.56716-0.27326-1.0374-0.59291-1.4121-0.95703-0.37493-0.36397-0.65723-0.78726-0.84961-1.2637-0.18217-0.48586-0.27149-1.0495-0.27149-1.6973 0-0.62831 0.13642-1.2242 0.41993-1.7812 0.28346-0.55707 0.70863-1.0403 1.2656-1.4551 0.56712-0.42501 1.2669-0.75957 2.1074-1.002 0.84053-0.2434 1.8251-0.36718 2.9492-0.36718zm50.994 0h0.00196c1.1546 0 2.1788 0.17104 3.0801 0.51562 0.90176 0.33411 1.669 0.83975 2.2969 1.5078 0.62831 0.66853 1.1016 1.5017 1.4258 2.4941 0.33411 0.98212 0.50391 2.1158 0.50391 3.4121 0 1.2152-0.17304 2.329-0.51758 3.3418-0.34469 1.0028-0.84224 1.863-1.4902 2.582-0.64784 0.71906-1.4429 1.2788-2.3848 1.6738-0.9419 0.39515-2.0137 0.5957-3.2188 0.5957-1.1441 0-2.167-0.17129-3.0684-0.50586-0.90172-0.33411-1.669-0.83195-2.2969-1.4902-0.62785-0.66852-1.1091-1.4939-1.4434-2.4863-0.33411-1.0028-0.49804-2.1695-0.49804-3.4961 0-1.2254 0.17311-2.3392 0.51758-3.3418 0.35452-1.0028 0.85387-1.8556 1.502-2.5644 0.65828-0.70874 1.4547-1.2597 2.3965-1.6445 0.94194-0.39515 2.0085-0.59375 3.1934-0.59375zm17.133 0c1.1545 0 2.1847 0.17104 3.0859 0.51562 0.90172 0.33411 1.6631 0.83975 2.291 1.5078 0.62831 0.66853 1.1073 1.5017 1.4316 2.4941 0.33411 0.98212 0.49805 2.1158 0.49805 3.4121 0 1.2152-0.17109 2.329-0.51563 3.3418-0.34469 1.0028-0.84238 1.863-1.4902 2.582-0.64781 0.71906-1.439 1.2788-2.3809 1.6738-0.9419 0.39515-2.0176 0.5957-3.2227 0.5957-1.1441 0-2.1689-0.17129-3.0703-0.50586-0.90172-0.33411-1.6632-0.83195-2.291-1.4902-0.62831-0.66852-1.1075-1.4939-1.4414-2.4863-0.33411-1.0028-0.50391-2.1695-0.50391-3.4961 0-1.2254 0.17108-2.3392 0.51563-3.3418 0.35489-1.0028 0.85928-1.8556 1.5078-2.5644 0.6582-0.70874 1.4566-1.2597 2.3984-1.6445 0.94189-0.39515 2.0026-0.59375 3.1875-0.59375zm-223.15 0.0332c0.45579 0 0.89731 0.02225 1.3125 0.06836 0.42535 0.04611 0.80805 0.12093 1.1523 0.23242h5.3477v2.7012h-2.4336c0.2835 0.3549 0.48233 0.73181 0.59375 1.127 0.12151 0.38479 0.1836 0.77696 0.18359 1.1816 0 0.86037-0.15323 1.621-0.45703 2.2793-0.29374 0.65817-0.71101 1.2107-1.2578 1.6562-0.53677 0.44569-1.1877 0.78833-1.9473 1.0215-0.7494 0.22299-1.5827 0.33203-2.4941 0.33203-0.53666 0-1.0268-0.05792-1.4824-0.1543-0.45579-0.1149-0.80025-0.23642-1.0332-0.3789-0.17216 0.17234-0.32376 0.36154-0.44531 0.57422-0.12151 0.2022-0.17774 0.43698-0.17774 0.70117 0 0.16176 0.03646 0.32629 0.11719 0.48828 0.08156 0.16176 0.19959 0.30676 0.35156 0.4375 0.16207 0.1217 0.34989 0.22305 0.5625 0.30469 0.22284 0.08012 0.46881 0.13237 0.74219 0.14257l3.6621 0.13672c0.82027 0.02268 1.5566 0.12132 2.2148 0.31446 0.66842 0.18255 1.2447 0.44557 1.7207 0.78906 0.47601 0.34469 0.84047 0.7678 1.0938 1.2637 0.26328 0.48586 0.39062 1.0396 0.39062 1.668 0 0.71905-0.15629 1.3991-0.48047 2.0371-0.32402 0.63753-0.82026 1.1904-1.4785 1.6562-0.64808 0.47554-1.4555 0.85358-2.4277 1.127-0.97223 0.27326-2.1025 0.41016-3.3887 0.41016-1.2456 0-2.3155-0.10337-3.2168-0.3086-0.89113-0.19313-1.6259-0.46342-2.2031-0.81836-0.5671-0.35489-0.99027-0.78097-1.2637-1.2773-0.27338-0.48586-0.41016-1.02-0.41016-1.6074 0-0.3549 0.04609-0.68193 0.13672-0.98633 0.09075-0.30425 0.22796-0.60107 0.41016-0.88477 0.18232-0.27325 0.40814-0.53655 0.68164-0.80078 0.27337-0.26305 0.59991-0.526 0.97461-0.78906-0.49623-0.28384-0.87724-0.65389-1.1406-1.0996-0.25315-0.44564-0.37891-0.91695-0.37891-1.4238 0-0.34469 0.04414-0.66099 0.13477-0.95507 0.09075-0.3035 0.20979-0.5946 0.35156-0.86915 0.15186-0.27325 0.3232-0.53632 0.51563-0.78906 0.19241-0.25247 0.39859-0.50258 0.61133-0.74609-0.37471-0.37455-0.6902-0.82718-0.94336-1.3535-0.24306-0.53643-0.36719-1.2019-0.36719-2.002 0-0.86037 0.15323-1.6286 0.45703-2.2969 0.31393-0.67887 0.74612-1.2416 1.293-1.6973 0.54688-0.46639 1.192-0.81762 1.9414-1.0508 0.7595-0.2434 1.5834-0.36133 2.4746-0.36133zm-39.842 0.30273h8.1562v12.549h4.4531v2.8848h-13.143v-2.8848h4.8906v-9.6641h-4.3574v-2.8848zm117.33 0h3.5254l1.1113 8.7188 0.2539 2.3809 0.62305-2.1367 1.5508-4.6953h2.8242l1.6562 4.6602 0.71289 2.207 0.29101-2.4453 0.92579-8.6895h3.3594l-2.1719 15.434h-4.1192l-1.6387-4.8926-0.50586-1.7031-0.49804 1.7461-1.5781 4.8496h-4.1328l-2.1894-15.434zm-77.518 2.2793c-0.45568 0-0.85486 0.06814-1.1992 0.22461-0.33415 0.15156-0.61321 0.35871-0.83594 0.62305-0.22276 0.25285-0.38858 0.553-0.5 0.89648-0.11142 0.34469-0.16601 0.7056-0.16601 1.0801 0 0.82012 0.237 1.4729 0.71289 1.959 0.48611 0.47554 1.1568 0.7129 2.0176 0.7129 0.45579 0 0.85329-0.0701 1.1875-0.22657 0.34427-0.15156 0.62688-0.35851 0.8496-0.61133 0.22285-0.25247 0.39249-0.54464 0.50391-0.8789 0.11142-0.33411 0.16602-0.68553 0.16602-1.0508 0-0.86037-0.24446-1.5298-0.73047-2.0059-0.47601-0.4859-1.1451-0.72266-2.0059-0.72266zm51.863 0.14844c-0.93169 0-1.6935 0.31513-2.291 0.94336-0.59739 0.61799-0.95889 1.4947-1.0703 2.6289h6.4121c0.00756-0.60767-0.06404-1.1386-0.22656-1.584-0.16214-0.456-0.38631-0.82904-0.66992-1.123-0.27364-0.29404-0.59409-0.51102-0.96875-0.65234-0.36473-0.14098-0.76016-0.21289-1.1856-0.21289zm-205.77 0.4375c-0.6482 0-1.2006 0.12687-1.6562 0.38086-0.45568 0.25247-0.83519 0.6051-1.1289 1.0508-0.29374 0.43533-0.51059 0.94402-0.65234 1.5312-0.13164 0.58696-0.19531 1.2203-0.19531 1.8887 0 1.6102 0.32446 2.8243 0.97265 3.6445 0.6482 0.80984 1.536 1.2168 2.6602 1.2168 0.61775 0 1.1517-0.12492 1.6074-0.36719 0.45568-0.25247 0.82784-0.59784 1.1113-1.0332 0.28358-0.44572 0.4929-0.96312 0.63476-1.5606 0.14174-0.59731 0.21289-1.2411 0.21289-1.9297 0-1.6-0.30068-2.8035-0.9082-3.6035-0.59754-0.80984-1.4835-1.2168-2.6582-1.2168v-0.00195zm68.535 0c-0.64819 0-1.2005 0.12687-1.6562 0.38086-0.45567 0.25247-0.82737 0.6051-1.1211 1.0508-0.29371 0.43533-0.51048 0.94402-0.65234 1.5312-0.13164 0.58696-0.19727 1.2203-0.19727 1.8887 0 1.6102 0.32067 2.8243 0.96875 3.6445 0.6482 0.80984 1.5341 1.2168 2.6582 1.2168 0.61776 0 1.1595-0.12492 1.6152-0.36719 0.45567-0.25247 0.8199-0.59784 1.1035-1.0332 0.28358-0.44572 0.50084-0.96312 0.64258-1.5606 0.14173-0.59731 0.21289-1.2411 0.21289-1.9297 0-1.6-0.30642-2.8035-0.91406-3.6035-0.59742-0.80984-1.4854-1.2168-2.6602-1.2168v-0.00195zm188.48 0c-0.64808 0-1.2006 0.12687-1.6562 0.38086-0.4557 0.25247-0.82739 0.6051-1.1211 1.0508-0.29367 0.43533-0.51242 0.94402-0.6543 1.5312-0.13191 0.58696-0.19531 1.2203-0.19531 1.8887 0 1.6102 0.32657 2.8243 0.97461 3.6445 0.64811 0.80984 1.5282 1.2168 2.6523 1.2168 0.61776 0 1.1595-0.12492 1.6152-0.36719 0.4557-0.25247 0.8199-0.59784 1.1035-1.0332 0.28346-0.44572 0.49889-0.96312 0.64062-1.5606 0.14136-0.59731 0.21485-1.2411 0.21485-1.9297 0-1.6-0.30647-2.8035-0.91406-3.6035-0.59755-0.80984-1.4854-1.2168-2.6602-1.2168v-0.00195zm102.81 0c-0.64785 0-1.2005 0.12687-1.6562 0.38086-0.45604 0.25247-0.829 0.6051-1.123 1.0508-0.29405 0.43533-0.51107 0.94402-0.65234 1.5312-0.13229 0.58696-0.19532 1.2203-0.19532 1.8887 0 1.6102 0.32094 2.8243 0.96875 3.6445 0.64785 0.80984 1.5341 1.2168 2.6582 1.2168 0.61799 0 1.1537-0.12492 1.6094-0.36719 0.45604-0.25247 0.82565-0.59784 1.1094-1.0332 0.28384-0.44572 0.49934-0.96312 0.64062-1.5606 0.14136-0.59731 0.21484-1.2411 0.21484-1.9297 0-1.6-0.30856-2.8035-0.91601-3.6035-0.59732-0.80984-1.4834-1.2168-2.6582-1.2168v-0.00195zm17.137 0c-0.64784 0-1.2005 0.12687-1.6562 0.38086-0.45604 0.25247-0.82701 0.6051-1.1211 1.0508-0.29405 0.43533-0.51106 0.94402-0.65234 1.5312-0.13229 0.58696-0.20313 1.2203-0.20313 1.8887 0 1.6102 0.32481 2.8243 0.97266 3.6445 0.64785 0.80984 1.5361 1.2168 2.6602 1.2168 0.618 0 1.1537-0.12492 1.6094-0.36719 0.45604-0.25247 0.82682-0.59784 1.1094-1.0332 0.28384-0.44572 0.49344-0.96312 0.63476-1.5606 0.14135-0.59731 0.21289-1.2411 0.21289-1.9297 0-1.6-0.30042-2.8035-0.9082-3.6035-0.59728-0.80984-1.4834-1.2168-2.6582-1.2168v-0.00195zm34.15 6.957c0.425 0 0.81706 0.08087 1.1816 0.24414 0.36396 0.15156 0.67917 0.3674 0.94336 0.64063 0.27363 0.26305 0.48313 0.5781 0.63476 0.94336 0.15156 0.36434 0.23242 0.75526 0.23242 1.1699 0 0.4055-0.08086 0.7875-0.23242 1.1523-0.15156 0.3549-0.3612 0.66999-0.63476 0.94336-0.26306 0.26306-0.57811 0.47223-0.94336 0.625-0.36397 0.16176-0.75618 0.24219-1.1816 0.24219-0.40551 0-0.78687-0.07891-1.1406-0.24219-0.3549-0.15156-0.66273-0.3607-0.92578-0.625-0.26306-0.27326-0.47028-0.58846-0.62305-0.94336-0.15156-0.36435-0.23047-0.74733-0.23047-1.1523 0-0.41466 0.07967-0.80577 0.23047-1.1699 0.15156-0.36397 0.35875-0.67918 0.62305-0.94336 0.26305-0.27326 0.57198-0.48899 0.92578-0.64063 0.3549-0.16176 0.73629-0.24414 1.1406-0.24414zm-259.11 5.4258c-0.29374 0.20183-0.53221 0.39464-0.72461 0.57617-0.18229 0.18255-0.33385 0.36078-0.44531 0.53321-0.10107 0.18255-0.16843 0.36523-0.20899 0.54687-0.02985 0.18255-0.04687 0.37217-0.04687 0.57422 0 0.59732 0.29906 1.0412 0.89648 1.3242 0.60765 0.28384 1.459 0.42773 2.5527 0.42773 0.69873 0 1.2877-0.07008 1.7637-0.20312 0.48612-0.13229 0.87786-0.30223 1.1816-0.51563 0.30384-0.21241 0.52071-0.45846 0.65234-0.74218 0.13165-0.28385 0.20118-0.5815 0.20118-0.88477 0-0.27326-0.06942-0.50233-0.20118-0.69531-0.12151-0.18255-0.29635-0.34373-0.52929-0.47461-0.22284-0.1217-0.49271-0.21448-0.80664-0.28516-0.30384-0.05745-0.64093-0.10226-1.0156-0.12305l-3.2695-0.05859z" />
            </g>
            </svg>
            </div>
            </div>
            </div>
            </div>';
    }

    function navigation($permission) {
        $data = '<nav class="fixed-top navbar navbar-expand-md navbar-dark bg-dark" style="box-shadow: black 0px 0px 4px;"><div class="container"> <a class="navbar-brand" href="../index.php"><svg width="12em" version="1.1" viewBox="0 0 106.53 18.638" xmlns="http://www.w3.org/2000/svg" fill="#ff5a00"><g transform="translate(-35.835 -38.875)"><path d="m43.285 38.875v11.51h-5.4704v-11.505c-1.2387 1.5318-1.9763 4.006-1.9789 6.6455-2.39e-4 4.5173 2.1109 8.1796 4.7141 8.1792 2.6032 3.7e-4 4.712-3.662 4.7118-8.1792-0.0017-2.641-0.73723-5.1182-1.9766-6.6508zm50.155 4.4828v9.5148h1.9834v-9.5148zm46.947 0v9.5148h1.9834v-9.5148zm-74.813 0.03164c-0.33473 0-0.60845 0.0942-0.82184 0.28248-0.2134 0.18829-0.32015 0.43136-0.32015 0.72844 0 0.2887 0.10675 0.53286 0.32015 0.7337 0.21338 0.19664 0.48711 0.29529 0.82184 0.29529 0.33892 0 0.61338-0.09605 0.82259-0.28851 0.2134-0.19247 0.32015-0.43923 0.32015-0.74048 0-0.29708-0.10675-0.54015-0.32015-0.72844-0.2092-0.18829-0.48368-0.28248-0.82259-0.28248zm10.601 1.1051-1.9774 0.56422v1.3876h-1.0418v1.4621h1.0418v2.9439c0 1.4519 0.6989 2.1778 2.0964 2.1778 0.58996 0 1.0314-0.07795 1.3243-0.23277v-1.4682c-0.22175 0.12134-0.43712 0.18154-0.64632 0.18154-0.53139 0-0.79698-0.33467-0.79698-1.0041v-2.5981h1.4433v-1.4621h-1.4433zm48.728 0-1.9766 0.56422v1.3876h-1.0418v1.4621h1.0418v2.9439c0 1.4519 0.69892 2.1778 2.0964 2.1778 0.58999 0 1.0314-0.07795 1.3243-0.23277v-1.4682c-0.22178 0.12134-0.43712 0.18154-0.64632 0.18154-0.53141 0-0.79774-0.33467-0.79774-1.0041v-2.5981h1.4441v-1.4621h-1.4441zm-35.793 1.7943c-0.39313 6.6e-5 -0.83059 0.05475-1.3115 0.16346-0.477 0.10878-0.85379 0.23438-1.1299 0.37664v1.4938c0.69038-0.45607 1.4188-0.68399 2.1845-0.68399 0.76152 0 1.142 0.35092 1.142 1.0539l-1.7446 0.23277c-1.477 0.19247-2.2154 0.91205-2.2154 2.1589 0 0.58997 0.17768 1.0628 0.53333 1.4184 0.35984 0.35147 0.8515 0.52731 1.4749 0.52731 0.8452 0 1.4831-0.35979 1.9141-1.0795h0.02561v0.92203h1.8764v-3.841c0-1.8282-0.91662-2.7425-2.7488-2.7427zm14.762 0c-1.0962 0-1.9728 0.31583-2.6297 0.94764-0.65273 0.62762-0.97928 1.488-0.97928 2.58 0 0.94562 0.30511 1.7173 0.916 2.3156 0.61089 0.59833 1.4103 0.89793 2.3977 0.89793 0.8452 0 1.492-0.13003 1.9397-0.38945v-1.594c-0.47281 0.30963-0.95636 0.46402-1.4501 0.46402-0.55649 0-0.99348-0.16068-1.3115-0.48286-0.318-0.32636-0.47683-0.77408-0.47683-1.3431 0-0.58578 0.1651-1.0442 0.49566-1.3748 0.33474-0.33473 0.78613-0.50169 1.3552-0.50169 0.51046 0 0.97335 0.15441 1.3876 0.46403v-1.6821c-0.33891-0.20085-0.88712-0.30132-1.6444-0.30132zm6.1002 0c-1.0837 0-1.9433 0.30143-2.5793 0.90395-0.63599 0.59834-0.95442 1.4291-0.95442 2.4919 0 1.0293 0.30587 1.8449 0.91675 2.4474 0.61507 0.59833 1.4584 0.89793 2.5296 0.89793 1.0879 0 1.943-0.30956 2.5665-0.92881 0.62763-0.61926 0.94162-1.4626 0.94162-2.5296 0-0.98746-0.3033-1.7806-0.90998-2.3789-0.6067-0.60252-1.4438-0.90395-2.5107-0.90395zm8.7804 0c-0.90378 0-1.592 0.39119-2.0648 1.1736h-0.0249v-1.0162h-1.9834v6.4263h1.9834v-3.6648c0-0.40586 0.11048-0.74054 0.3322-1.0041 0.22179-0.2636 0.50434-0.39548 0.84745-0.39548 0.7113 0 1.0667 0.49795 1.0667 1.4938v3.5706h1.9774v-3.9352c0-1.7657-0.71147-2.6486-2.1341-2.6486zm16.927 0c-1.0837 0-1.9433 0.30143-2.5793 0.90395-0.63602 0.59834-0.95443 1.4291-0.95443 2.4919 0 1.0293 0.30586 1.8449 0.91676 2.4474 0.61507 0.59833 1.4577 0.89793 2.5288 0.89793 1.0879 0 1.9438-0.30956 2.5672-0.92881 0.62763-0.61926 0.94161-1.4626 0.94161-2.5296 0-0.98746-0.30324-1.7806-0.90997-2.3789-0.60669-0.60252-1.4438-0.90395-2.5107-0.90395zm-63.741 0.04444c-0.81591 0-1.3811 0.43515-1.6949 1.3055h-0.02486v-1.1925h-1.9834v6.4263h1.9834v-3.0689c0-0.54394 0.12116-0.97281 0.36384-1.2866 0.2427-0.318 0.57995-0.47683 1.0109-0.47683 0.31799 0 0.59615 0.06463 0.83465 0.19435v-1.826c-0.11716-0.0502-0.28042-0.07533-0.48964-0.07533zm59.222 0c-0.8159 0-1.3804 0.43515-1.6942 1.3055h-0.0256v-1.1925h-1.9834v6.4263h1.9834v-3.0689c0-0.54394 0.12111-0.97281 0.36385-1.2866 0.24266-0.318 0.57994-0.47683 1.0109-0.47683 0.31801 0 0.59614 0.06463 0.83465 0.19435v-1.826c-0.11705-0.0502-0.28056-0.07533-0.48964-0.07533zm-74.379 0.11299 2.278 6.4263h2.2591l2.3917-6.4263h-2.0716l-1.142 3.9977c-0.12554 0.43934-0.20272 0.80725-0.23201 1.1043h-0.02561c-0.02087-0.31381-0.09367-0.69428-0.2192-1.142l-1.1171-3.9601zm7.7883 0v6.4263h1.9834v-6.4263zm14.059 0v3.8787c0 1.8034 0.74545 2.7051 2.235 2.7051 0.8201 0 1.4743-0.37863 1.9638-1.136h0.03164v0.97853h1.9774v-6.4263h-1.9774v3.6776c0 0.42678-0.10677 0.76589-0.32015 1.0169-0.21341 0.24687-0.49783 0.36986-0.85348 0.36986-0.71967 0-1.0795-0.45399-1.0795-1.362v-3.7024zm31.306 1.362c0.9582 0 1.4373 0.60027 1.4373 1.8011 0 1.2678-0.47467 1.9013-1.4245 1.9013-0.99582 0-1.4938-0.61651-1.4938-1.8508 0-0.58996 0.13005-1.0465 0.38946-1.3687 0.25941-0.32218 0.62289-0.48286 1.0915-0.48286zm25.708 0c0.95818 0 1.4373 0.60027 1.4373 1.8011 0 1.2678-0.47466 1.9013-1.4245 1.9013-0.99584 0-1.4938-0.61651-1.4938-1.8508 0-0.58996 0.1293-1.0465 0.3887-1.3687 0.25931-0.32218 0.62365-0.48286 1.0923-0.48286zm-85.997 0.27872c-2.6032-2.39e-4 -4.712 2.1087-4.7118 4.7118-1.89e-4 2.6033 2.1087 4.7143 4.7118 4.7141 1.5218-0.0019 2.9503-0.73916 3.8335-1.9781h-6.3909l2.7194-5.4712h3.6685c-0.88266-1.2388-2.3095-1.9741-3.8305-1.9766zm40.35 1.7424v0.43239c0 0.39331-0.11677 0.71986-0.35104 0.97928-0.23431 0.25522-0.53758 0.38267-0.90998 0.38267-0.26777 0-0.4813-0.07091-0.6403-0.21318-0.15483-0.14645-0.23277-0.33224-0.23277-0.55819 0-0.49792 0.32286-0.78938 0.96723-0.87306zm-50.883 0.98305c0.35202 1.58e-4 0.63698 0.28518 0.63804 0.63728-1.58e-4 0.35264-0.2854 0.63863-0.63804 0.63879-0.35264-1.58e-4 -0.63863-0.28605-0.63879-0.63879 1e-3 -0.35202 0.28679-0.63712 0.63879-0.63728zm2.8776 0c0.35266-7.45e-4 0.63924 0.28457 0.6403 0.63728-1.58e-4 0.35327-0.28684 0.63954-0.6403 0.63879-0.35266-1.58e-4 -0.63863-0.28605-0.63879-0.63879 1e-3 -0.35202 0.28676-0.63712 0.63879-0.63728zm5.1653 0.07985c0.46293-7.98e-4 0.83831 0.37476 0.83841 0.83766-9.53e-4 0.46229-0.37613 0.83622-0.83841 0.8354-0.46104-9.57e-4 -0.83445-0.37434-0.8354-0.8354 1.04e-4 -0.46168 0.37375-0.83668 0.8354-0.83766z"></path></g></svg>
            <br></a> <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar2SupportedContent" aria-controls="navbar2SupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
            <div class="navbar-collapse text-center justify-content-end collapse" id="navbar2SupportedContent">
            <ul class="navbar-nav w-100">';
        if($permission == 1) {
            $data .= '<li class="nav-item mx-auto"> <a class="nav-link active" href="../index.php"><i class="fa fa-fw fa-2x fa-home nav-icon"></i><span class="navbar-text">HOME</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../analy.php"><i class="fa fa-fw fa-2x fa-bar-chart nav-icon"></i><span class="navbar-text">ANALY</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../warn.php"><i class="fa fa-fw fa-2x fa-exclamation-triangle nav-icon"></i><span class="navbar-text">WARN</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="https://github.com/ClearNB/Virtual-Control"><i class="fab fa-github-square fa-2x nav-icon"></i><span class="navbar-text">GITHUB</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../option"><i class="fa fa-fw fa-2x fa-wrench nav-icon"></i><span class="navbar-text">OPTION</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../help.php"><i class="fa fa-fw fa-2x fa-info-circle nav-icon"></i><span class="navbar-text">HELP</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../logout.php"><i class="fa fa-fw fa-2x fa-power-off nav-icon text-danger"></i><span class="navbar-text">LOGOUT</span></a> </li>';
        } else {
            $data .= '<li class="nav-item mx-auto"> <a class="nav-link active" href="https://github.com/ClearNB/Virtual-Control"><i class="fab fa-github-square fa-2x nav-icon"></i><span class="navbar-text">GITHUB</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../help.php"><i class="fas fa-question-circle fa-2x nav-icon"></i><span class="navbar-text">HELP</span></a> </li>
            <li class="nav-item mx-auto"> <a class="nav-link active" href="../login.php"><i class="fas fa-sign-in-alt fa-2x nav-icon"></i><span class="navbar-text">LOGIN</span></a> </li>';
        }
        $data .= '</ul></div></div></nav>';
        return $data;
    }
    
    function footerS($ishideC = false) {
        $hide_text = '';
        if ($ishideC) { $hide_text = '.'; }
        echo '<script src="js/jquery.js"></script>
	      <script src="js/ajax_dynamic.js"></script>
	      <script src="js/animation.js"></script>
	      <script src="js/acc_check.js"></script>
              <script src="js/popper.min.js"></script>
              <script src="js/bootstrap.min.js"></script>';
    }
    
    function footer() {
        echo '<div class="bg-dark pt-0">
            <div class="container">
            <div class="row">
            <div class="col-md-12 my-3 text-center">
            <p>© 2020 Project GSC All Rights Reserved.<br></p>
            </div>
            </div>
            </div>
            </div>';
    }
}
