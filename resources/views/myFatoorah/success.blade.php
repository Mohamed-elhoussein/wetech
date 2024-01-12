<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <title>تم الدفع بنجاح</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        * {
            font-family: Tajawal, sans-serif !important;
        }

        body {
            direction: rtl;
            margin: 0;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: var(--bs-body-bg);
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        h6,
        h5,
        h4 {
            margin-top: 0;
            margin-bottom: .5rem;
            font-weight: 700;
            line-height: 1.1;
            color: #364a63;
        }

        h4 {
            font-size: 1.25rem;
        }

        h5 {
            font-size: 1.15rem;
        }

        h6 {
            font-size: 1rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        ul {
            padding-left: 2rem;
        }

        ul {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        ul ul {
            margin-bottom: 0;
        }

        strong {
            font-weight: bolder;
        }

        small {
            font-size: 85%;
        }

        a {
            color: var(--bs-link-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--bs-link-hover-color);
            text-decoration: none;
        }

        img {
            vertical-align: middle;
        }

        .container {
            --bs-gutter-x: 14px;
            --bs-gutter-y: 0;
            width: 100%;
            padding-right: calc(var(--bs-gutter-x)*.5);
            padding-left: calc(var(--bs-gutter-x)*.5);
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }

        @media (min-width: 1540px) {
            .container {
                max-width: 1440px;
            }
        }

        .row {
            --bs-gutter-x: 28px;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1*var(--bs-gutter-y));
            margin-right: calc(-0.5*var(--bs-gutter-x));
            margin-left: calc(-0.5*var(--bs-gutter-x));
        }

        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x)*.5);
            padding-left: calc(var(--bs-gutter-x)*.5);
            margin-top: var(--bs-gutter-y);
        }

        .gy-2 {
            --bs-gutter-y: 0.75rem;
        }

        .g-3 {
            --bs-gutter-x: 1rem;
        }

        .g-3 {
            --bs-gutter-y: 1rem;
        }

        @media (min-width: 992px) {
            .col-lg-6 {
                flex: 0 0 auto;
                width: 50%;
            }
        }

        .btn {
            --bs-btn-padding-x: 1.125rem;
            --bs-btn-padding-y: 0.4375rem;
            --bs-btn-font-family: Nunito, sans-serif;
            --bs-btn-font-size: 0.8125rem;
            --bs-btn-font-weight: 700;
            --bs-btn-line-height: 1.25rem;
            --bs-btn-color: #526484;
            --bs-btn-bg: transparent;
            --bs-btn-border-width: 1px;
            --bs-btn-border-color: transparent;
            --bs-btn-border-radius: 4px;
            --bs-btn-hover-border-color: transparent;
            --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(16, 25, 36, 0.075);
            --bs-btn-disabled-opacity: 0.5;
            --bs-btn-focus-box-shadow: 0 0 0 0.2rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
            display: inline-block;
            padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
            font-family: var(--bs-btn-font-family);
            font-size: var(--bs-btn-font-size);
            font-weight: var(--bs-btn-font-weight);
            line-height: var(--bs-btn-line-height);
            color: var(--bs-btn-color);
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
            border-radius: var(--bs-btn-border-radius);
            background-color: var(--bs-btn-bg);
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        @media (prefers-reduced-motion: reduce) {
            .btn {
                transition: none;
            }
        }

        .btn:hover {
            color: var(--bs-btn-hover-color);
            background-color: var(--bs-btn-hover-bg);
            border-color: var(--bs-btn-hover-border-color);
        }

        :not(.btn-check)+.btn:active {
            color: var(--bs-btn-active-color);
            background-color: var(--bs-btn-active-bg);
            border-color: var(--bs-btn-active-border-color);
        }

        .btn:disabled {
            color: var(--bs-btn-disabled-color);
            pointer-events: none;
            background-color: var(--bs-btn-disabled-bg);
            border-color: var(--bs-btn-disabled-border-color);
            opacity: var(--bs-btn-disabled-opacity);
        }

        .fade {
            transition: opacity .15s linear;
        }

        @media (prefers-reduced-motion: reduce) {
            .fade {
                transition: none;
            }
        }

        .fade:not(.show) {
            opacity: 0;
        }

        .dropup {
            position: relative;
        }

        .dropdown-toggle {
            white-space: nowrap;
        }

        .dropdown-toggle::after {
            display: inline-block;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid rgba(0, 0, 0, 0);
            border-bottom: 0;
            border-left: .3em solid rgba(0, 0, 0, 0);
        }

        .dropdown-toggle:empty::after {
            margin-left: 0;
        }

        .dropdown-menu {
            --bs-dropdown-zindex: 1000;
            --bs-dropdown-min-width: 180px;
            --bs-dropdown-padding-x: 0;
            --bs-dropdown-padding-y: 0;
            --bs-dropdown-spacer: 0.125rem;
            --bs-dropdown-font-size: 0.8125rem;
            --bs-dropdown-color: #526484;
            --bs-dropdown-bg: #fff;
            --bs-dropdown-border-color: #e5e9f2;
            --bs-dropdown-border-radius: 4px;
            --bs-dropdown-border-width: 1px;
            --bs-dropdown-inner-border-radius: calc(4px - 1px);
            --bs-dropdown-divider-bg: #e5e9f2;
            --bs-dropdown-divider-margin-y: 12px;
            --bs-dropdown-box-shadow: 0 3px 12px 1px rgba(44, 55, 130, 0.15);
            --bs-dropdown-link-color: #364a63;
            --bs-dropdown-link-hover-color: #6576ff;
            --bs-dropdown-link-hover-bg: #ebeef2;
            --bs-dropdown-link-active-color: #6576ff;
            --bs-dropdown-link-active-bg: #dbdfea;
            --bs-dropdown-link-disabled-color: #ebeef2;
            --bs-dropdown-item-padding-x: 14px;
            --bs-dropdown-item-padding-y: 8px;
            --bs-dropdown-header-color: #8091a7;
            --bs-dropdown-header-padding-x: 14px;
            --bs-dropdown-header-padding-y: 0;
            position: absolute;
            z-index: var(--bs-dropdown-zindex);
            display: none;
            min-width: var(--bs-dropdown-min-width);
            padding: var(--bs-dropdown-padding-y) var(--bs-dropdown-padding-x);
            margin: 0;
            font-size: var(--bs-dropdown-font-size);
            color: var(--bs-dropdown-color);
            text-align: left;
            list-style: none;
            background-color: var(--bs-dropdown-bg);
            background-clip: padding-box;
            border: var(--bs-dropdown-border-width) solid var(--bs-dropdown-border-color);
            border-radius: var(--bs-dropdown-border-radius);
        }

        .dropdown-menu-end {
            --bs-position: end;
        }

        .dropup .dropdown-toggle::after {
            display: inline-block;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: 0;
            border-right: .3em solid rgba(0, 0, 0, 0);
            border-bottom: .3em solid;
            border-left: .3em solid rgba(0, 0, 0, 0);
        }

        .dropup .dropdown-toggle:empty::after {
            margin-left: 0;
        }

        .nav {
            --bs-nav-link-padding-x: 1rem;
            --bs-nav-link-padding-y: 0.5rem;
            --bs-nav-link-color: var(--bs-link-color);
            --bs-nav-link-hover-color: var(--bs-link-hover-color);
            --bs-nav-link-disabled-color: #6c757d;
            display: flex;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }

        .nav-link {
            display: block;
            padding: var(--bs-nav-link-padding-y) var(--bs-nav-link-padding-x);
            font-size: var(--bs-nav-link-font-size);
            font-weight: var(--bs-nav-link-font-weight);
            color: var(--bs-nav-link-color);
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
        }

        @media (prefers-reduced-motion: reduce) {
            .nav-link {
                transition: none;
            }
        }

        .nav-link:hover,
        .nav-link:focus {
            color: var(--bs-nav-link-hover-color);
        }

        .badge {
            --bs-badge-padding-x: 0.375rem;
            --bs-badge-padding-y: 0;
            --bs-badge-font-size: 0.675rem;
            --bs-badge-font-weight: 500;
            --bs-badge-color: #fff;
            --bs-badge-border-radius: 3px;
            display: inline-block;
            padding: var(--bs-badge-padding-y) var(--bs-badge-padding-x);
            font-size: var(--bs-badge-font-size);
            font-weight: var(--bs-badge-font-weight);
            line-height: 1;
            color: var(--bs-badge-color);
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: var(--bs-badge-border-radius);
        }

        .badge:empty {
            display: none;
        }

        .modal {
            --bs-modal-zindex: 1051;
            --bs-modal-width: 520px;
            --bs-modal-padding: 1.25rem;
            --bs-modal-margin: 0.5rem;
            --bs-modal-bg: #fff;
            --bs-modal-border-color: rgba(0, 0, 0, 0);
            --bs-modal-border-width: 0;
            --bs-modal-border-radius: 5px;
            --bs-modal-box-shadow: 0px 0px 1px 0px rgba(82, 100, 132, 0.2), 0px 8px 15.52px 0.48px rgba(28, 43, 70, 0.15);
            --bs-modal-inner-border-radius: 4px;
            --bs-modal-header-padding-x: 1.25rem;
            --bs-modal-header-padding-y: 1rem;
            --bs-modal-header-padding: 1rem 1.25rem;
            --bs-modal-header-border-color: var(--bs-border-color);
            --bs-modal-header-border-width: 1px;
            --bs-modal-title-line-height: 1.5;
            --bs-modal-footer-gap: 0.5rem;
            --bs-modal-footer-border-color: var(--bs-border-color);
            --bs-modal-footer-border-width: 1px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: var(--bs-modal-zindex);
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: var(--bs-modal-margin);
            pointer-events: none;
        }

        .modal.fade .modal-dialog {
            transition: transform .3s ease-out;
            transform: translate(0, -30px);
        }

        @media (prefers-reduced-motion: reduce) {
            .modal.fade .modal-dialog {
                transition: none;
            }
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            color: var(--bs-modal-color);
            pointer-events: auto;
            background-color: var(--bs-modal-bg);
            background-clip: padding-box;
            border: var(--bs-modal-border-width) solid var(--bs-modal-border-color);
            border-radius: var(--bs-modal-border-radius);
            outline: 0;
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: var(--bs-modal-padding);
        }

        @media (min-width: 576px) {
            .modal {
                --bs-modal-margin: 1.75rem;
                --bs-modal-box-shadow: 0px 0px 1px 0px rgba(82, 100, 132, 0.2), 0px 8px 15.52px 0.48px rgba(28, 43, 70, 0.15);
            }

            .modal-dialog {
                max-width: var(--bs-modal-width);
                margin-right: auto;
                margin-left: auto;
            }
        }

        @media (min-width: 992px) {
            .modal-lg {
                --bs-modal-width: 720px;
            }
        }

        .justify-content-center {
            justify-content: center !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .pb-5 {
            padding-bottom: 2.75rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-primary {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-primary-rgb), var(--bs-text-opacity)) !important;
        }

        .text-success {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-success-rgb), var(--bs-text-opacity)) !important;
        }

        .text-white {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-white-rgb), var(--bs-text-opacity)) !important;
        }

        .bg-primary {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-primary-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-success {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-success-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-info {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-info-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-warning {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-warning-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-danger {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-danger-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-light {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-light-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-dark {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-lighter {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-lighter-rgb), var(--bs-bg-opacity)) !important;
        }

        .bg-white {
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-white-rgb), var(--bs-bg-opacity)) !important;
        }

        @media (min-width: 992px) {
            .justify-content-lg-end {
                justify-content: flex-end !important;
            }

            .order-lg-last {
                order: 6 !important;
            }
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        a {
            transition: color .4s, background-color .4s, border .4s, box-shadow .4s;
        }

        a:focus {
            outline: none;
        }

        img {
            max-width: 100%;
        }

        strong {
            font-weight: 500;
        }

        p:last-child {
            margin-bottom: 0;
        }

        h4 {
            letter-spacing: -0.02em;
        }

        h5,
        h6 {
            letter-spacing: -0.01em;
        }

        @media (min-width: 992px) {
            h4 {
                font-size: 1.5rem;
            }

            h5 {
                font-size: 1.25rem;
            }

            h6 {
                font-size: 1.05rem;
            }
        }

        small {
            font-weight: 400 !important;
        }

        .text-soft {
            color: #8094ae !important;
        }

        .bg-blue {
            background-color: #559bfb !important;
        }

        .bg-azure {
            background-color: #1676fb !important;
        }

        .bg-indigo {
            background-color: #2c3782 !important;
        }

        .bg-purple {
            background-color: #816bff !important;
        }

        .bg-pink {
            background-color: #ff63a5 !important;
        }

        .bg-orange {
            background-color: #ffa353 !important;
        }

        .bg-teal {
            background-color: #20c997 !important;
        }

        @media (min-width: 1200px) {
            .wide-lg {
                max-width: 1140px !important;
            }
        }

        .badge {
            position: relative;
            border: 1px solid currentColor;
            line-height: 1.125rem;
            font-family: Roboto, sans-serif;
            font-size: .675rem;
            letter-spacing: .01em;
            vertical-align: middle;
            display: inline-flex;
        }

        .bg-primary {
            border-color: #6576ff;
            background: #6576ff;
        }

        .bg-success {
            border-color: #1ee0ac;
            background: #1ee0ac;
        }

        .bg-info {
            border-color: #09c2de;
            background: #09c2de;
        }

        .bg-warning {
            border-color: #f4bd0e;
            background: #f4bd0e;
        }

        .bg-danger {
            border-color: #e85347;
            background: #e85347;
        }

        .bg-light {
            border-color: #e5e9f2;
            background: #e5e9f2;
        }

        .bg-dark {
            border-color: #1f2b3a;
            background: #1f2b3a;
        }

        .bg-lighter {
            border-color: #f5f6fa;
            background: #f5f6fa;
        }

        .btn {
            position: relative;
            letter-spacing: .02em;
            display: inline-flex;
            align-items: center;
        }

        .btn .icon {
            font-size: 1.4em;
            line-height: inherit;
        }

        .btn:active {
            border-color: rgba(0, 0, 0, 0) !important;
        }

        .btn-icon:not([class*=btn-icon-break]) {
            padding-left: 0;
            padding-right: 0;
        }

        .btn-icon .icon {
            width: 2.125rem;
        }

        .btn-trigger {
            position: relative;
            z-index: 1;
            color: #526484;
        }

        .btn-trigger:active {
            border-color: rgba(0, 0, 0, 0) !important;
        }

        .btn-trigger:focus {
            box-shadow: none;
        }

        .btn-trigger:before {
            position: absolute;
            z-index: -1;
            height: 20px;
            width: 20px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transform-origin: 50% 50%;
            content: "";
            background-color: #e5e9f2;
            border-radius: 50%;
            opacity: 0;
            transition: all .3s;
        }

        .btn-trigger:hover:before,
        .btn-trigger:focus:before {
            opacity: 1;
            height: 120%;
            width: 120%;
        }

        .dropup {
            display: inline-flex;
        }

        .dropdown-toggle {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            margin-bottom: 0;
        }

        .dropdown-menu {
            overflow: hidden;
            border: 1px solid #e5e9f2;
            box-shadow: 0 3px 12px 1px rgba(44, 55, 130, .15);
        }

        [class*=dropdown-indicator]:after {
            border: none !important;
            font-family: "Nioicon";
            vertical-align: middle;
            content: "";
            margin-left: .25rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
        }

        .dropup [class*=dropdown-indicator]:after {
            content: "";
        }

        .dropdown-menu-sm {
            min-width: 140px;
            max-width: 140px;
        }

        .nav {
            margin: -0.5rem -1rem;
        }

        .nav .nav-link {
            display: inline-flex;
            align-items: center;
        }

        .nav-sm {
            font-size: .8125rem;
        }

        .modal-content {
            position: relative;
            min-height: 40px;
            box-shadow: 0px 0px 1px 0px rgba(82, 100, 132, .2), 0px 8px 15.52px .48px rgba(28, 43, 70, .15);
        }

        .modal-content>.close {
            position: absolute;
            top: .75rem;
            right: .75rem;
            height: 2.25rem;
            width: 2.25rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #526484;
            z-index: 1;
            transition: all .3s;
        }

        .modal-body-md {
            padding: 1.75rem 1.25rem;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal-dialog::before {
            height: calc(100vh - 1rem);
        }

        .modal.fade .modal-dialog {
            transform: translate(0, -10px);
        }

        @media (min-width: 576px) {
            .modal-dialog {
                min-height: calc(100% - 3.5rem);
            }

            .modal-dialog::before {
                height: calc(100vh - 3.5rem);
            }

            .modal-body {
                padding: 1.5rem 1.5rem;
            }

            .modal-body-md {
                padding: 2.25rem 2.5rem;
            }
        }

        .ni {
            font-family: "Nioicon" !important;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .ni-menu-squared:before {
            content: "";
        }

        .ni-dashlite:before {
            content: "";
        }

        .ni-arrow-long-right:before {
            content: "";
        }

        .ni-cross-sm:before {
            content: "";
        }

        .ni-cross:before {
            content: "";
        }

        .ni-cart:before {
            content: "";
        }

        .ni-setting-alt:before {
            content: "";
        }

        [data-simplebar] {
            position: relative;
            flex-direction: column;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-content: flex-start;
            align-items: flex-start;
        }

        .simplebar-wrapper {
            overflow: hidden;
            width: inherit;
            height: inherit;
            max-width: inherit;
            max-height: inherit;
        }

        .simplebar-mask {
            direction: inherit;
            position: absolute;
            overflow: hidden;
            padding: 0;
            margin: 0;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            width: auto !important;
            height: auto !important;
            z-index: 0;
        }

        .simplebar-offset {
            direction: inherit !important;
            box-sizing: inherit !important;
            resize: none !important;
            position: absolute;
            top: 0;
            left: 0 !important;
            bottom: 0;
            right: 0 !important;
            padding: 0;
            margin: 0;
            -webkit-overflow-scrolling: touch;
        }

        .simplebar-content-wrapper {
            direction: inherit;
            box-sizing: border-box !important;
            position: relative;
            display: block;
            height: 100%;
            width: auto;
            visibility: visible;
            max-width: 100%;
            max-height: 100%;
            scrollbar-width: none;
            -ms-overflow-style: none;
            overflow: hidden scroll;
        }

        .simplebar-content-wrapper::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        .simplebar-content:before,
        .simplebar-content:after {
            content: " ";
            display: table;
        }

        .simplebar-placeholder {
            max-height: 100%;
            max-width: 100%;
            width: 100%;
            pointer-events: none;
        }

        .simplebar-height-auto-observer-wrapper {
            box-sizing: inherit !important;
            height: 100%;
            width: 100%;
            max-width: 1px;
            position: relative;
            float: left;
            max-height: 1px;
            overflow: hidden;
            z-index: -1;
            padding: 0;
            margin: 0;
            pointer-events: none;
            flex-grow: inherit;
            flex-shrink: 0;
            flex-basis: 0;
        }

        .simplebar-height-auto-observer {
            box-sizing: inherit;
            display: block;
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            height: 1000%;
            width: 1000%;
            min-height: 1px;
            min-width: 1px;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        .simplebar-track {
            z-index: 1;
            position: absolute;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .simplebar-scrollbar {
            position: absolute;
            left: 0;
            right: 0;
            min-height: 10px;
        }

        .simplebar-scrollbar:before {
            position: absolute;
            content: "";
            background: #8094ae;
            border-radius: 7px;
            left: 2px;
            right: 2px;
            opacity: 0;
            transition: opacity .2s linear;
        }

        .simplebar-track.simplebar-vertical {
            top: 0;
            width: 8px !important;
            transition: width .1s;
        }

        .simplebar-track.simplebar-vertical .simplebar-scrollbar:before {
            top: 2px;
            bottom: 2px;
        }

        .simplebar-track.simplebar-horizontal {
            left: 0;
            height: 8px !important;
            transition: height .1s;
        }

        .simplebar-track.simplebar-horizontal .simplebar-scrollbar:before {
            height: 100%;
            left: 2px;
            right: 2px;
        }

        .simplebar-track.simplebar-horizontal .simplebar-scrollbar {
            right: auto;
            left: 0;
            top: 2px;
            height: 7px;
            min-height: 0;
            min-width: 10px;
            width: auto;
        }

        body {
            min-width: 320px;
        }

        .nk-body {
            outline: none;
        }

        .nk-app-root {
            outline: none;
        }

        .nk-main {
            position: relative;
        }

        .nk-wrap {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .nk-content {
            padding: 24px 4px;
        }

        @media (min-width: 576px) {
            .nk-content {
                padding: 32px 22px;
            }
        }

        .nk-wrap-nosidebar .nk-content {
            padding: 0 !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .logo-link {
            position: relative;
            display: inline-block;
            align-items: center;
        }

        .logo-dark {
            opacity: 1;
        }

        .logo-light {
            opacity: 0;
        }

        .logo-img {
            max-height: 36px;
        }

        .logo-img-lg {
            max-height: 60px;
        }

        .logo-img:not(:first-child) {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .nk-footer {
            margin-top: auto;
            background: #fff;
            border-top: 1px solid #e5e9f2;
            padding: 20px 6px;
        }

        @media (min-width: 576px) {
            .nk-footer {
                padding: 20px 22px;
            }
        }

        a:hover {
            text-decoration: none;
        }

        p:last-child {
            margin-bottom: 0;
        }

        li {
            list-style: none;
        }

        .gy-2:not(.row) {
            margin-top: -0.375rem;
            margin-bottom: -0.375rem;
        }

        .gy-2:not(.row)>li {
            padding-top: .375rem;
            padding-bottom: .375rem;
        }

        .toggle-slide {
            position: fixed;
            top: 0;
            z-index: 999;
            min-width: 260px;
            max-width: calc(100% - 40px);
            transition: transform 650ms ease;
        }

        .toggle-slide-right {
            right: 0;
            transform: translateX(100%);
        }

        .nk-block-middle {
            margin-top: auto;
            margin-bottom: auto;
        }

        .nk-block-head {
            position: relative;
            padding-bottom: 1.25rem;
        }

        .nk-block-des {
            color: #526484;
        }

        .language-list li:not(:last-child) .language-item {
            border-bottom: 1px solid #ecf2ff;
        }

        .language-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #344357;
            transition: all .4s;
        }

        .language-item:hover {
            color: #3c4d62;
            background: #ebeef2;
        }

        .language-name {
            font-size: 12px;
        }

        .language-flag {
            width: 24px;
            margin-right: 12px;
        }

        .nk-auth-body {
            padding: 1.25rem;
        }

        .nk-auth-body {
            width: 100%;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .nk-auth-footer-full {
            margin-top: 0;
        }

        .country-list {
            display: flex;
            flex-wrap: wrap;
        }

        .country-list li {
            width: 100%;
        }

        .country-item {
            display: flex;
            align-items: center;
        }

        .country-flag {
            width: 1.25rem;
            margin-right: .75rem;
        }

        .country-name {
            font-size: 1rem;
            color: #526484;
        }

        @media (min-width: 576px) {
            .country-list li {
                width: 50%;
            }
        }

        @media (min-width: 992px) {
            .country-list li {
                width: 33.33%;
            }
        }

        .close {
            float: right;
            font-size: 1.505rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: .5;
        }

        .close:hover {
            color: #000;
            text-decoration: none;
        }

        .nk-sticky-toolbar {
            position: fixed;
            border: 3px solid #fff;
            top: 50%;
            background: #fff;
            z-index: 600;
            right: 0;
            border-radius: 6px 0 0 6px;
            border-right: 0;
            box-shadow: -2px 0 24px -2px rgba(43, 55, 72, .15);
        }

        .nk-sticky-toolbar li:not(:last-child) {
            border-bottom: 2px solid #fff;
        }

        .nk-sticky-toolbar li a {
            display: flex;
            height: 36px;
            width: 36px;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: inherit;
        }

        .demo-layout {
            background-color: rgba(85, 155, 251, .1);
            color: #559bfb;
        }

        .demo-thumb {
            background-color: rgba(30, 224, 172, .1);
            color: #1ee0ac;
        }

        .demo-settings {
            background-color: rgba(129, 107, 255, .1);
            color: #816bff;
        }

        .demo-purchase {
            background-color: rgba(255, 99, 165, .1);
            color: #ff63a5;
        }

        @media (min-width: 576px) {
            .nk-sticky-toolbar {
                top: 30%;
            }

            .nk-sticky-toolbar li a {
                font-size: 20px;
                height: 44px;
                width: 44px;
            }

            .nk-sticky-toolbar li.demo-thumb a {
                font-size: 22px;
            }
        }

        .nk-demo-panel {
            position: fixed;
            right: 0;
            top: 0;
            width: 320px;
            max-width: calc(100vw - 40px);
            max-height: 100vh;
            height: 100vh;
            z-index: 9999;
            background-color: #fff;
            box-shadow: 0 3px 12px 1px rgba(43, 55, 72, .15);
            padding: 0 0 1.5rem;
        }

        .nk-demo-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1.5rem;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e9f2;
        }

        .nk-demo-head h6 {
            font-size: 13px;
            letter-spacing: .1em;
        }

        .nk-demo-list {
            overflow: auto;
            max-height: calc(100vh - 60px);
        }

        .nk-demo-title {
            display: block;
            font-size: .875rem;
            padding: .675rem 0;
            text-align: center;
            color: #526484;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }

        .nk-demo-item {
            padding: 2rem 1.5rem 1rem;
            border-bottom: 1px solid #e5e9f2;
        }

        .nk-demo-item a {
            display: block;
        }

        .nk-demo-item a:hover .nk-demo-title {
            color: #6576ff;
        }

        .nk-demo-image {
            border: 1px solid #e5e9f2;
            border-radius: 4px 4px 0 0;
            padding: 1rem 1rem 0 1rem;
        }

        .nk-demo-image img {
            border-radius: inherit;
        }

        @media (min-width: 576px) {
            .nk-demo-item {
                padding: 2rem 2rem 1rem;
            }

            .nk-demo-head {
                padding: 15px 2rem 13px;
            }
        }

        @media (min-width: 768px) {
            .nk-demo-panel-2x {
                width: 640px;
            }

            .nk-demo-panel-2x .nk-demo-list .simplebar-content {
                display: flex;
                flex-wrap: wrap;
            }

            .nk-demo-panel-2x .nk-demo-list .simplebar-content .nk-demo-item {
                width: 50%;
            }

            .nk-demo-panel-2x .nk-demo-list .simplebar-content .nk-demo-item:not(:nth-child(2n)) {
                border-right: 1px solid #e5e9f2;
            }
        }

        .nk-opt-panel {
            overflow: auto;
            max-height: calc(100vh - 84px);
        }

        .nk-opt-reset {
            padding: 1.5rem 1.5rem 1.5rem;
        }

        .nk-opt-set {
            padding: 1.5rem 1.5rem 1.5rem;
        }

        .nk-opt-set:not(:last-child) {
            border-bottom: 1px solid #e5e9f2;
        }

        .nk-opt-set-title {
            padding: 0 0 .5rem;
            text-transform: uppercase;
            color: #8094ae;
            letter-spacing: 2px;
            font-weight: 500;
            font-size: 11px;
        }

        .nk-opt-list {
            padding: 0;
            flex-wrap: wrap;
            margin: -0.5rem;
            display: flex;
        }

        .nk-opt-list+.nk-opt-set-title {
            margin-top: 1.25rem;
        }

        .nk-opt-item {
            width: 33%;
            text-align: center;
            padding: .5rem;
        }

        .nk-opt-item:not(.active):not(.disabled) {
            cursor: pointer;
        }

        .nk-opt-item.active {
            cursor: default;
        }

        .col-2x .nk-opt-item {
            width: 50%;
        }

        .col-4x .nk-opt-item {
            width: 25%;
        }

        .nk-opt-item-name {
            font-weight: 400;
            color: #526484;
            font-size: 12px;
            letter-spacing: .02em;
        }

        .nk-opt-item-bg {
            background: #fff;
            width: 100%;
            height: 32px;
            border: 1px solid #dbdfea;
            padding: 3px;
            margin-bottom: 5px;
            position: relative;
            display: block;
            border-radius: 2px;
            transition: all .3s;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name) {
            display: block;
            background: #526484;
            height: 100%;
            border-radius: 1px;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).bg-theme {
            background: #2c3782 !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).bg-light {
            background: #ebeef2 !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).bg-lighter {
            background: #f5f6fa !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).bg-dark {
            background: #101924 !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).theme-light {
            background: linear-gradient(90deg, #f5f6fa 0%, #f5f6fa 50%, #e5e9f2 50%, #e5e9f2 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).theme-dark {
            background: linear-gradient(90deg, #1f2b3a 0%, #1f2b3a 50%, #101924 50%, #101924 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).skin-default {
            background: linear-gradient(90deg, #2c3782 0%, #2c3782 28%, #6576ff 28%, #6576ff 72%, #c4cefe 72%, #c4cefe 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).skin-purple {
            background: linear-gradient(90deg, #4700e8 0%, #4700e8 28%, #854fff 28%, #854fff 72%, #e7dcff 72%, #e7dcff 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).skin-green {
            background: linear-gradient(90deg, #074e3b 0%, #074e3b 28%, #0fac81 28%, #0fac81 72%, #cfeee6 72%, #cfeee6 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).skin-blue {
            background: linear-gradient(90deg, #0144a0 0%, #0144a0 30%, #0971fe 30%, #0971fe 72%, #cee3ff 72%, #cee3ff 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).skin-egyptian {
            background: linear-gradient(90deg, #1a3767 0%, #1a3767 30%, #2e62b9 30%, #2e62b9 72%, #d5e0f1 72%, #d5e0f1 100%) !important;
        }

        .nk-opt-item-bg>span:not(.nk-opt-item-name).skin-red {
            background: linear-gradient(90deg, #ab0e21 0%, #ab0e21 30%, #ee3148 30%, #ee3148 72%, #fcd6da 72%, #fcd6da 100%) !important;
        }

        .nk-opt-item-bg:hover,
        .nk-opt-item-bg:focus {
            border-color: #b7c2d0;
        }

        .active>.nk-opt-item-bg {
            border-color: #6576ff;
            box-shadow: 0 0 0 2px rgba(101, 118, 255, .2);
        }

        .active>.nk-opt-item-bg:after {
            font-family: "Nioicon";
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: .875rem;
        }

        .active>.nk-opt-item-bg.is-light:after {
            color: #101924;
        }

        .only-text>.nk-opt-item-bg {
            margin-bottom: 0;
            height: 28px;
        }

        .only-text>.nk-opt-item-bg:after {
            display: none;
        }

        .nk-opt-item-bg>.nk-opt-item-name {
            color: #364a63;
            font-size: 10px;
            line-height: 1;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: .12em;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
        }

        .active>.nk-opt-item-bg>.nk-opt-item-name {
            color: #6576ff;
            font-weight: 700;
        }

        @media (min-width: 576px) {

            .nk-opt-set,
            .nk-opt-reset {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        .pmo-lv {
            position: fixed;
            left: 50%;
            bottom: -50px;
            max-width: 90%;
            width: 590px;
            transform: translate(-50%, 100%);
            transition: all .4s;
            background: #fff;
            border-radius: 50px;
            box-shadow: 0 0 40px -2px rgba(31, 43, 58, .25);
            z-index: 9999;
        }

        .pmo-lv.pmo-dark {
            background: #0564e5;
        }

        .pmo-close {
            color: #364a63;
            border-radius: 50%;
            height: 36px;
            width: 36px;
            line-height: 38px;
            background: rgba(183, 194, 208, .6);
            text-align: center;
            display: inline-block;
            position: absolute;
            top: 50%;
            margin-top: -18px;
            right: 8px;
            font-size: 16px;
            z-index: 99;
        }

        .pmo-close:hover {
            color: #fff;
            background: #526484;
        }

        .pmo-dark .pmo-close {
            background: rgba(31, 43, 58, .3);
            color: #fff;
        }

        .pmo-dark .pmo-close:hover {
            background: rgba(31, 43, 58, .5);
        }

        .pmo-wrap {
            display: flex;
            padding: .875rem 3.5rem .875rem 1.5rem;
            align-items: center;
            border-radius: 50px;
            transition: all 300ms;
        }

        .pmo-wrap:hover {
            transform: translateX(5px);
        }

        .pmo-text {
            font-family: Roboto, sans-serif;
            font-size: 14px;
            line-height: 22px;
            font-weight: 500;
            letter-spacing: .02em;
            color: #e85347;
            transition: color .4s;
        }

        .pmo-text .ni {
            font-size: 20px;
            display: inline-block;
            vertical-align: middle;
            margin-left: .125rem;
            margin-top: -3px;
        }

        .pmo-dark .pmo-text {
            color: #fff;
        }

        .pmo-st {
            position: fixed;
            right: 25px;
            bottom: 0;
            display: flex;
            align-items: center;
            transition: all .4s;
            border-radius: 30px;
            transform: translateY(100%);
            box-shadow: 0 5px 40px 0 rgba(16, 25, 36, .3);
            color: #fff;
            background: #e85347;
            z-index: 99999;
        }

        .pmo-st:active,
        .pmo-st:focus,
        .pmo-st:hover {
            color: #fff;
        }

        .pmo-st.pmo-dark {
            background: #0564e5;
        }

        .pmo-st.active {
            bottom: 25px;
            transform: translateY(0);
            transition-delay: .4s;
        }

        .pmo-st-img {
            width: 60px;
            height: 60px;
            border-radius: 30px;
            padding: 18px 18px;
            transition: all .4s;
        }

        .pmo-st-text {
            padding: 14px 0;
            height: 60px;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-weight: 600;
            transition: all .4s;
            visibility: hidden;
            font-size: 12px;
            line-height: 16px;
        }

        .pmo-st:hover .pmo-st-text {
            width: 220px;
            visibility: visible;
            padding: 14px 14px 14px 0;
        }

        a {
            color: var(--bs-link-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--bs-link-hover-color);
            text-decoration: none;
        }

        .btn {
            --bs-btn-padding-x: 1.125rem;
            --bs-btn-padding-y: 0.4375rem;
            --bs-btn-font-family: Nunito, sans-serif;
            --bs-btn-font-size: 0.8125rem;
            --bs-btn-font-weight: 700;
            --bs-btn-line-height: 1.25rem;
            --bs-btn-color: #526484;
            --bs-btn-bg: transparent;
            --bs-btn-border-width: 1px;
            --bs-btn-border-color: transparent;
            --bs-btn-border-radius: 4px;
            --bs-btn-hover-border-color: transparent;
            --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(16, 25, 36, 0.075);
            --bs-btn-disabled-opacity: 0.5;
            --bs-btn-focus-box-shadow: 0 0 0 0.2rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
            display: inline-block;
            padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
            font-family: var(--bs-btn-font-family);
            font-size: var(--bs-btn-font-size);
            font-weight: var(--bs-btn-font-weight);
            line-height: var(--bs-btn-line-height);
            color: var(--bs-btn-color);
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
            border-radius: var(--bs-btn-border-radius);
            background-color: var(--bs-btn-bg);
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        @media (prefers-reduced-motion: reduce) {
            .btn {
                transition: none;
            }
        }

        .btn:hover {
            color: var(--bs-btn-hover-color);
            background-color: var(--bs-btn-hover-bg);
            border-color: var(--bs-btn-hover-border-color);
        }

        :not(.btn-check)+.btn:active {
            color: var(--bs-btn-active-color);
            background-color: var(--bs-btn-active-bg);
            border-color: var(--bs-btn-active-border-color);
        }

        .btn:disabled {
            color: var(--bs-btn-disabled-color);
            pointer-events: none;
            background-color: var(--bs-btn-disabled-bg);
            border-color: var(--bs-btn-disabled-border-color);
            opacity: var(--bs-btn-disabled-opacity);
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #6576ff;
            --bs-btn-border-color: #6576ff;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #5664d9;
            --bs-btn-hover-border-color: #515ecc;
            --bs-btn-focus-shadow-rgb: 124, 139, 255;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #515ecc;
            --bs-btn-active-border-color: #4c59bf;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(16, 25, 36, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #6576ff;
            --bs-btn-disabled-border-color: #6576ff;
        }

        a {
            transition: color .4s, background-color .4s, border .4s, box-shadow .4s;
        }

        a:focus {
            outline: none;
        }

        .btn {
            position: relative;
            letter-spacing: .02em;
            display: inline-flex;
            align-items: center;
        }

        .btn:active {
            border-color: rgba(0, 0, 0, 0) !important;
        }

        a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body class="nk-body bg-white npc-default pg-auth no-touch nk-nio-theme">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap nk-wrap-nosidebar">
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body">
                        <div class="brand-logo pb-5">
                            <a href="/" class="logo-link">
                                <img class="logo-dark logo-img logo-img-lg" src="/assets/images/favicon.ico" srcset="/assets/images/favicon.ico 2x" alt="logo-dark">
                            </a>
                        </div>
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title">تم استلام دفعتك بنجاح، شكراً لثقتك بنا</h4>
                                <div class="nk-block-des text-success">
                                    <p>
                                        تمت عملية الدفع بنجاح! شكراً لثقتك بنا. سوف يتم معالجة طلبك في أقرب وقت وسيتم إرسال تفاصيل الطلب إلى بريدك الإلكتروني. إذا كان لديك أي أسئلة أو استفسارات، يرجى عدم التردد في الاتصال بفريق دعم العملاء لدينا. شكراً مرة أخرى لتسوقك معنا!
                                    </p>
                                </div>
                                <a href="/" style="margin-top: 10px;" class="mt-3 btn btn-primary">
                                    العودة للرئيسية
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="nk-footer nk-auth-footer-full">
                        <div class="container wide-lg">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="nk-block-content text-center text-lg-left">
                                        <p class="text-soft" dir="ltr">© {{ now()->format('Y') }} {{ config('app.name') }}. كل الحقوق محفوظة.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
