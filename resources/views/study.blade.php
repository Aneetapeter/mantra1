<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mantra Study Space">
    <meta name="author" content="Mantra">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <title>Mantra | Study Space</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=4">

    <style>
        /* Fix split background issue */
        html,
        body {
            height: 100%;
            margin: 0;
            background: #0b1120;
            /* Same as editor background */
        }

        body.light-mode,
        body.light-mode html {
            background: #f0f4ff !important;
        }

        /* Notion-style Calendar Colors */
        :root {
            --cal-bg: #0F1115;
            --cal-cell: #161A22;
            --cal-text: #E6E6E6;
            --cal-muted: #8A8F98;
            --cal-border: #222631;
            --cal-study: #5C7CFA;
            --cal-completed: #4CAF50;
            --cal-review: #FBC02D;
            --cal-exam: #E53935;
            --cal-personal: #8E6CEF;
            --cal-inactive: #8A8F98;
            --hour-height: 60px;
        }

        /* View Toggle Buttons */
        .view-toggle {
            display: flex;
            background: var(--cal-border);
            border-radius: 6px;
            overflow: hidden;
            margin-right: 12px;
        }

        .view-btn {
            padding: 6px 14px;
            background: transparent;
            border: none;
            color: var(--cal-muted);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .view-btn:hover {
            color: var(--cal-text);
        }

        .view-btn.active {
            background: var(--cal-study);
            color: white;
        }

        /* Weekly View Styles */
        .week-header {
            display: grid;
            grid-template-columns: 60px repeat(7, 1fr);
            border-bottom: 1px solid var(--cal-border);
            position: sticky;
            top: 0;
            z-index: 10;
            background: var(--cal-bg);
        }

        .week-time-gutter {
            width: 60px;
            background: var(--cal-bg);
        }

        .week-day-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 4px;
            border-left: 1px solid var(--cal-border);
        }

        .week-day-header .day-name {
            font-size: 11px;
            color: var(--cal-muted);
            text-transform: uppercase;
        }

        .week-day-header .day-num {
            font-size: 24px;
            font-weight: 600;
            color: var(--cal-text);
            margin-top: 4px;
        }

        .week-day-header.today .day-num {
            background: var(--cal-study);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .week-grid-container {
            height: auto;
            overflow: visible;
        }

        .week-grid {
            display: grid;
            grid-template-columns: 60px repeat(7, 1fr);
            position: relative;
        }

        .week-hour-row {
            display: contents;
        }

        .week-time-label {
            position: relative;
            font-size: 11px;
            color: var(--cal-muted);
            text-align: right;
            padding-right: 8px;
            height: var(--hour-height);
            display: flex;
            align-items: flex-start;
            padding-top: 0;
        }

        .week-time-label span {
            transform: translateY(-8px);
        }

        .week-day-column {
            height: var(--hour-height);
            border-left: 1px solid var(--cal-border);
            border-bottom: 1px solid var(--cal-border);
            position: relative;
            cursor: pointer;
        }

        .week-day-column:hover {
            background: rgba(92, 124, 250, 0.05);
        }

        .week-day-column:hover::after {
            content: '+ Add';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 9px;
            color: rgba(92, 124, 250, 0.5);
            pointer-events: none;
            white-space: nowrap;
            letter-spacing: 0.5px;
        }

        /* Week view events */
        .week-event {
            position: absolute;
            left: 2px;
            right: 2px;
            border-radius: 4px;
            padding: 4px 6px;
            font-size: 11px;
            overflow: hidden;
            cursor: pointer;
            z-index: 5;
            border-left: 3px solid;
        }

        .week-event .event-title {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .week-event .event-time {
            font-size: 10px;
            opacity: 0.8;
        }

        .calendar-layout {
            background: var(--cal-bg);
            display: flex;
            min-height: calc(100vh - 60px);
            height: auto;
            overflow: visible;
        }

        /* Make cal-main scrollable */
        .cal-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        /* Month view scrollable container */
        #month-view {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: visible;
            padding-bottom: 20px;
        }

        #month-view .main-grid-body {
            flex: 1;
            min-height: 500px;
        }

        .grid-cell {
            background: var(--cal-cell) !important;
            border-color: var(--cal-border) !important;
        }

        .grid-cell:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        /* Week Event Styling */
        .week-event {
            position: absolute;
            left: 2px;
            right: 2px;
            padding: 4px 6px;
            border-radius: 4px;
            border-left: 3px solid;
            font-size: 11px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 2px;
            pointer-events: auto;
            cursor: pointer;
            z-index: 10;
        }

        .week-event .event-title {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .week-event .event-time {
            font-size: 10px;
            opacity: 0.8;
        }

        /* Notion-style Event Cards with Full Colored Backgrounds */
        .event-block {
            display: flex;
            flex-direction: column;
            padding: 6px 10px;
            margin-bottom: 4px;
            border-radius: 6px;
            font-size: 11px;
            cursor: grab;
            transition: all 0.15s ease;
            border-left: 4px solid;
            color: var(--cal-text);
            min-height: 28px;
        }

        .event-block:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .event-block.dragging {
            opacity: 0.5;
            cursor: grabbing;
        }

        .event-block .event-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .event-block.completed {
            opacity: 0.5;
        }

        .event-block.completed .event-title {
            text-decoration: line-through;
        }

        /* Full colored backgrounds for each type */
        .event-block.study {
            background: rgba(92, 124, 250, 0.15);
            border-left-color: #5C7CFA;
        }

        .event-block.exam {
            background: rgba(229, 57, 53, 0.15);
            border-left-color: #E53935;
        }

        .event-block.meeting {
            background: rgba(142, 108, 239, 0.15);
            border-left-color: #8E6CEF;
        }

        .event-block.birthday,
        .event-block.review {
            background: rgba(251, 192, 45, 0.15);
            border-left-color: #FBC02D;
        }

        /* Orange color for work/general */
        .event-block.work {
            background: rgba(255, 152, 0, 0.15);
            border-left-color: #FF9800;
        }

        /* Green for completed tasks */
        .event-block.done {
            background: rgba(76, 175, 80, 0.15);
            border-left-color: #4CAF50;
        }

        /* Grey for inactive */
        .event-block.cancelled {
            background: rgba(138, 143, 152, 0.15);
            border-left-color: #8A8F98;
        }

        /* Drop zone highlight */
        .grid-cell.drag-over {
            background: rgba(92, 124, 250, 0.1) !important;
            border: 1px dashed var(--cal-study) !important;
        }

        /* Event content layout */
        .event-content {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            min-width: 0;
        }

        .event-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Priority badges */
        .priority-badge {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .priority-badge.high {
            background: rgba(229, 57, 53, 0.2);
            color: #E53935;
        }

        .priority-badge.medium {
            background: rgba(251, 192, 45, 0.2);
            color: #FBC02D;
        }

        .priority-badge.low {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }

        /* Improved event block */
        .event-block {
            display: flex;
            flex-direction: column;
            padding: 6px 10px;
            margin-bottom: 4px;
            border-radius: 6px;
            font-size: 12px;
            cursor: grab;
            transition: all 0.15s ease;
            border-left: 3px solid var(--cal-study);
            color: var(--cal-text);
        }

        /* Event Modal Enhancements */
        .event-modal-content {
            background: var(--cal-cell);
            border: 1px solid var(--cal-border);
            border-radius: 12px;
            padding: 24px;
            width: 420px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .color-picker-row {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .color-option {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.15s;
        }

        .color-option:hover,
        .color-option.selected {
            transform: scale(1.1);
            border-color: white;
        }

        .form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            font-size: 12px;
            color: var(--cal-muted);
            margin-bottom: 6px;
            display: block;
        }

        .form-control-dark {
            background: var(--cal-bg);
            border: 1px solid var(--cal-border);
            color: var(--cal-text);
            border-radius: 6px;
            padding: 10px 12px;
            width: 100%;
        }

        .form-control-dark option {
            background: var(--cal-bg);
            color: var(--cal-text);
        }

        .form-control-dark:focus {
            outline: none;
            border-color: var(--cal-study);
        }

        /* Quick add inline */
        .quick-add-input {
            background: transparent;
            border: none;
            color: var(--cal-text);
            font-size: 12px;
            width: 100%;
            padding: 4px;
        }

        .quick-add-input:focus {
            outline: none;
        }

        .quick-add-input::placeholder {
            color: var(--cal-muted);
        }

        /* Editor.js Fixes */
        .codex-editor {
            z-index: 10 !important;
            position: relative;
            min-height: 300px;
            /* Ensure clickable area */
        }

        .codex-editor__redactor {
            padding-bottom: 50px !important;
        }

        .ce-block__content,
        .ce-toolbar__content {
            max-width: 100%;
            /* Use full width */
        }

        /* Quote / Comment Styling */
        .cdx-quote {
            background: #f8f9fa;
            color: #000000 !important;
            padding: 15px;
            border-left: 4px solid var(--accent-primary);
            border-radius: 6px;
            margin: 10px 0;
        }

        .cdx-quote__text {
            min-height: auto !important;
            color: #000000 !important;
        }

        .cdx-quote__caption {
            color: #555 !important;
        }

        /* General Editor Text Visibility */
        .ce-paragraph,
        .ce-header,
        .cdx-list__item,
        .cdx-checklist__item-text,
        .ce-code__textarea {
            color: var(--cal-text) !important;
        }

        /* Checklist specific */
        .cdx-checklist__item-checkbox {
            border-color: var(--cal-muted);
            background: transparent;
        }

        .cdx-checklist__item-checkbox-check {
            background: var(--cal-study);
            border-color: var(--cal-study);
        }

        /* FIX INVISIBLE LISTS - AGGRESSIVE */
        .codex-editor__redactor ul,
        .codex-editor__redactor ol,
        .codex-editor__redactor li,
        .cdx-nested-list,
        .cdx-nested-list__item,
        .cdx-list__item,
        .cdx-list__item-content,
        .ce-block__content {
            color: var(--cal-text) !important;
            list-style: inherit !important;
        }

        .cdx-nested-list {
            padding-left: 20px !important;
            margin-left: 10px !important;
        }

        /* Professional UI Polish */
        .codex-editor__redactor {
            padding-bottom: 300px !important;
            /* Space for scrolling */
            max-width: 900px !important;
            /* Wider editor */
            margin: 0 auto;
        }

        #note-editor-container {
            background: var(--cal-bg);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        /* Better Comments / Highlights */
        mark {
            background-color: #fef3c7;
            color: #d97706;
            padding: 2px 4px;
            border-radius: 4px;
        }

        /* ===== SMART NOTES THEME SYSTEM ===== */
        /* Dark Mode (default) — Pure Black */
        #smart-notes-app {
            --note-bg: #000000;
            --note-sidebar-bg: #0a0a0a;
            --note-list-bg: #0d0d0d;
            --note-editor-bg: #000000;
            --note-text: #e8e8e8;
            --note-text-muted: #888888;
            --note-border: #1a1a1a;
            --note-input-bg: #111111;
            --note-hover: rgba(92, 124, 250, 0.08);
            --note-active: rgba(92, 124, 250, 0.15);
            --note-toolbar-bg: #111111;
            --note-toolbar-btn: #b0b0b0;
            --note-toolbar-hover: #5C7CFA;
            --note-block-bg: #111111;
            --note-block-border: #1e1e1e;
            --note-code-bg: #0d0d0d;
            --note-table-bg: #111111;
            --note-table-border: #1e1e1e;
            --note-quote-border: #5C7CFA;
            --note-link: #5C7CFA;
            --note-placeholder: #444444;
            clear: both;
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 10px;
        }

        /* Light Mode Overrides for Smart Notes */
        body.light-mode #smart-notes-app {
            --note-bg: #ffffff;
            --note-sidebar-bg: #f8f9fa;
            --note-list-bg: #ffffff;
            --note-editor-bg: #ffffff;
            --note-text: #212529;
            --note-text-muted: #6c757d;
            --note-border: #e9ecef;
            --note-input-bg: #ffffff;
            --note-hover: rgba(92, 124, 250, 0.08);
            --note-active: rgba(92, 124, 250, 0.15);
            --note-toolbar-bg: #ffffff;
            --note-toolbar-btn: #495057;
            --note-toolbar-hover: #5C7CFA;
            --note-block-bg: #f8f9fa;
            --note-block-border: #dee2e6;
            --note-code-bg: #f8f9fa;
            --note-table-bg: #ffffff;
            --note-table-border: #dee2e6;
            --note-quote-border: #5C7CFA;
            --note-link: #5C7CFA;
            --note-placeholder: #adb5bd;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Force hide placeholder when editor is open */
        #note-editor-container[style*="flex"]~#editor-placeholder,
        #note-editor-container:not([style*="none"])~#editor-placeholder {
            display: none !important;
        }

        /* When note-editor-container is visible, hide placeholder */
        #smart-notes-app .editor-active #editor-placeholder {
            display: none !important;
        }



        /* ===== SMART NOTES CARD ===== */
        #smart-notes-app .card {
            background: var(--note-bg) !important;
            border: 1px solid var(--note-border) !important;
        }

        /* ===== SIDEBAR STYLING ===== */
        #smart-notes-app .notes-sidebar-col {
            background: var(--note-sidebar-bg) !important;
            border-right: 1px solid var(--note-border) !important;
            transition: max-width 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
            overflow: hidden;
        }

        #smart-notes-app .notes-sidebar-col h5,
        #smart-notes-app .notes-sidebar-col .text-uppercase {
            color: var(--note-text-muted) !important;
        }

        #smart-notes-app #notes-sidebar-menu a {
            color: var(--note-text) !important;
            border-radius: 6px;
            padding: 8px 12px;
            transition: all 0.2s;
        }

        #smart-notes-app #notes-sidebar-menu a:hover {
            background: var(--note-hover) !important;
        }

        #smart-notes-app #notes-sidebar-menu a.active {
            background: var(--note-active) !important;
            color: var(--note-link) !important;
        }

        /* ===== NOTES LIST STYLING ===== */
        #smart-notes-app .notes-list-col {
            background: var(--note-list-bg) !important;
            border-right: 1px solid var(--note-border) !important;
        }

        #smart-notes-app .notes-list-col h6 {
            color: var(--note-text) !important;
        }

        #smart-notes-app #search-notes {
            background: var(--note-input-bg) !important;
            color: var(--note-text) !important;
            border: 1px solid var(--note-border) !important;
        }

        #smart-notes-app #search-notes::placeholder {
            color: var(--note-placeholder) !important;
        }

        /* ===== OVERRIDE BOOTSTRAP CLASSES INSIDE NOTES ===== */
        #smart-notes-app .list-group-item {
            background-color: transparent !important;
            border-color: var(--note-border) !important;
            color: var(--note-text) !important;
        }

        #smart-notes-app .list-group-item-action:hover {
            background-color: var(--note-hover) !important;
            color: var(--note-text) !important;
        }

        #smart-notes-app .list-group-item-action.active {
            background-color: var(--note-active) !important;
            color: var(--note-text) !important;
        }

        #smart-notes-app .text-muted {
            color: var(--note-text-muted) !important;
        }

        #smart-notes-app .border-right {
            border-right-color: var(--note-border) !important;
        }

        #smart-notes-app .border-bottom {
            border-bottom-color: var(--note-border) !important;
        }

        #smart-notes-app .list-group-flush .list-group-item {
            border-left: 0 !important;
            border-right: 0 !important;
        }

        #smart-notes-app .btn-primary {
            background: var(--note-link) !important;
            border-color: var(--note-link) !important;
        }

        #smart-notes-app .nav-link {
            color: var(--note-text) !important;
        }

        #smart-notes-app .nav-link:hover {
            background: var(--note-hover) !important;
        }

        #smart-notes-app .nav-link.active {
            background: var(--note-active) !important;
            color: var(--note-link) !important;
        }

        #smart-notes-app .font-weight-bold {
            color: var(--note-text) !important;
        }

        #smart-notes-app .note-item {
            background: transparent !important;
            border-bottom: 1px solid var(--note-border) !important;
            color: var(--note-text) !important;
            cursor: pointer;
            transition: background 0.2s;
        }

        #smart-notes-app .note-item:hover {
            background: var(--note-hover) !important;
        }

        #smart-notes-app .note-item.active {
            background: var(--note-active) !important;
            border-left: 3px solid var(--note-link) !important;
        }

        #smart-notes-app .note-list-title {
            color: var(--note-text) !important;
        }

        #smart-notes-app .note-list-preview {
            color: var(--note-text-muted) !important;
        }

        /* ===== EDITOR AREA ===== */
        #smart-notes-app .notes-editor-col {
            background: var(--note-editor-bg) !important;
        }

        #smart-notes-app #note-title-input {
            color: var(--note-text) !important;
            background: transparent !important;
            font-weight: 700 !important;
        }

        #smart-notes-app #note-title-input::placeholder {
            color: var(--note-placeholder) !important;
        }

        #smart-notes-app #note-editor-container {
            border-top: 1px solid var(--note-border);
        }

        #smart-notes-app #note-editor-container .border-bottom {
            border-bottom-color: var(--note-border) !important;
        }

        #smart-notes-app #note-folder-select {
            background: var(--note-input-bg) !important;
            color: var(--note-text-muted) !important;
            border: 1px solid var(--note-border) !important;
        }

        /* ===== EDITOR.JS BLOCKS — DARK/LIGHT AWARE ===== */
        #smart-notes-app #quill-editor {
            color: var(--note-text) !important;
        }

        /* Paragraphs */
        #smart-notes-app .ce-paragraph,
        #smart-notes-app .ce-block__content {
            color: var(--note-text) !important;
        }

        #smart-notes-app .ce-paragraph[data-placeholder]::before {
            color: var(--note-placeholder) !important;
        }

        /* Headers */
        #smart-notes-app .ce-header {
            color: var(--note-text) !important;
        }

        /* Lists */
        #smart-notes-app .cdx-nested-list__item-content,
        #smart-notes-app .cdx-list__item {
            color: var(--note-text) !important;
        }

        /* Checklists */
        #smart-notes-app .cdx-checklist__item-text {
            color: var(--note-text) !important;
        }

        #smart-notes-app .cdx-checklist__item-checkbox {
            border-color: var(--note-border) !important;
            background: var(--note-block-bg) !important;
        }

        #smart-notes-app .cdx-checklist__item--checked .cdx-checklist__item-checkbox {
            background: var(--note-link) !important;
            border-color: var(--note-link) !important;
        }

        /* Tables */
        #smart-notes-app .tc-table {
            background: var(--note-table-bg) !important;
            border: 1px solid var(--note-table-border) !important;
            border-radius: 8px;
            overflow: hidden;
        }

        #smart-notes-app .tc-row {
            border-bottom: 1px solid var(--note-table-border) !important;
        }

        #smart-notes-app .tc-cell {
            border-right: 1px solid var(--note-table-border) !important;
            color: var(--note-text) !important;
            background: var(--note-table-bg) !important;
        }

        #smart-notes-app .tc-add-column,
        #smart-notes-app .tc-add-row {
            color: var(--note-text-muted) !important;
            background: var(--note-block-bg) !important;
        }

        /* Code blocks */
        #smart-notes-app .ce-code__textarea,
        #smart-notes-app .cdx-code {
            background: var(--note-code-bg) !important;
            color: var(--note-text) !important;
            border: 1px solid var(--note-block-border) !important;
            border-radius: 8px !important;
            font-family: 'Fira Code', 'Consolas', monospace !important;
        }

        /* Inline code */
        #smart-notes-app .inline-code {
            background: var(--note-code-bg) !important;
            color: #e06c75 !important;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Quote blocks */
        #smart-notes-app .cdx-quote {
            background: var(--note-block-bg) !important;
            border-left: 4px solid var(--note-quote-border) !important;
            border-radius: 0 8px 8px 0 !important;
            padding: 16px 20px !important;
        }

        #smart-notes-app .cdx-quote__text,
        #smart-notes-app .cdx-quote__caption {
            color: var(--note-text) !important;
        }

        /* Delimiter */
        #smart-notes-app .ce-delimiter {
            color: var(--note-border) !important;
        }

        /* Editor.js toolbar & inline tools */
        #smart-notes-app .ce-toolbar__plus,
        #smart-notes-app .ce-toolbar__settings-btn {
            color: var(--note-text-muted) !important;
            background: var(--note-block-bg) !important;
            border: 1px solid var(--note-border) !important;
        }

        #smart-notes-app .ce-toolbar__plus:hover,
        #smart-notes-app .ce-toolbar__settings-btn:hover {
            color: var(--note-link) !important;
        }

        #smart-notes-app .ce-inline-toolbar {
            background: var(--note-toolbar-bg) !important;
            border: 1px solid var(--note-border) !important;
            color: var(--note-text) !important;
        }

        #smart-notes-app .ce-inline-tool {
            color: var(--note-toolbar-btn) !important;
        }

        #smart-notes-app .ce-inline-tool:hover {
            color: var(--note-toolbar-hover) !important;
        }

        #smart-notes-app .ce-popover {
            background: var(--note-toolbar-bg) !important;
            border: 1px solid var(--note-border) !important;
        }

        #smart-notes-app .ce-popover-item__title {
            color: var(--note-text) !important;
        }

        #smart-notes-app .ce-popover-item__icon {
            color: var(--note-text-muted) !important;
            background: var(--note-block-bg) !important;
        }

        #smart-notes-app .ce-popover-item:hover {
            background: var(--note-hover) !important;
        }

        /* Custom toolbar row */
        #smart-notes-app .editor-toolbar-row {
            background: var(--note-toolbar-bg) !important;
            border-bottom: 1px solid var(--note-border) !important;
        }

        #smart-notes-app .editor-toolbar-row .toolbar-btn {
            color: var(--note-toolbar-btn) !important;
        }

        #smart-notes-app .editor-toolbar-row .toolbar-btn:hover {
            color: var(--note-toolbar-hover) !important;
            background: var(--note-hover) !important;
        }

        /* Placeholder area */
        #smart-notes-app #editor-placeholder {
            color: var(--note-text-muted) !important;
        }

        #smart-notes-app #editor-placeholder i {
            color: var(--note-text-muted) !important;
            opacity: 0.3;
        }

        /* ===== DARK/LIGHT MODE TOGGLE BUTTON ===== */
        .notes-mode-toggle {
            background: none;
            border: 1px solid rgba(108, 117, 125, 0.3);
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            color: #FBC02D;
        }

        .notes-mode-toggle:hover {
            background: rgba(251, 192, 45, 0.1);
            border-color: #FBC02D;
        }

        #smart-notes-app.notes-light-mode .notes-mode-toggle {
            color: #5C7CFA;
        }

        #smart-notes-app.notes-light-mode .notes-mode-toggle:hover {
            background: rgba(92, 124, 250, 0.1);
            border-color: #5C7CFA;
        }

        /* ===== FULLSCREEN MODE ===== */
        #smart-notes-app.smart-notes-fullscreen {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 5000;
            margin: 0 !important;
            padding: 0 !important;
            background: var(--cal-bg);
            border-radius: 0;
            border-top: none;
        }

        #smart-notes-app.smart-notes-fullscreen .col-md-12 {
            padding: 0;
            height: 100vh;
        }

        #smart-notes-app.smart-notes-fullscreen .card {
            min-height: 100vh !important;
            height: 100vh !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        #smart-notes-app.smart-notes-fullscreen .row.no-gutters {
            height: 100vh !important;
        }

        #smart-notes-app.smart-notes-fullscreen .col-md-3,
        #smart-notes-app.smart-notes-fullscreen .col-md-6 {
            height: 100vh !important;
        }

        /* ===== COLLAPSIBLE SIDEBAR ===== */
        #smart-notes-app .notes-sidebar-col {
            transition: max-width 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
            overflow: hidden;
        }

        #smart-notes-app.sidebar-collapsed .notes-sidebar-col {
            max-width: 0 !important;
            flex: 0 !important;
            padding: 0 !important;
            opacity: 0;
            border: none !important;
        }

        #smart-notes-app.sidebar-collapsed .notes-list-col {
            max-width: 0 !important;
            flex: 0 !important;
            padding: 0 !important;
            opacity: 0;
            border: none !important;
            overflow: hidden;
            transition: max-width 0.3s ease, opacity 0.3s ease;
        }

        #smart-notes-app.sidebar-collapsed .notes-editor-col {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Toggle buttons styling */
        .notes-toggle-btn {
            background: none;
            border: 1px solid rgba(108, 117, 125, 0.3);
            color: #6c757d;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 16px;
        }

        .notes-toggle-btn:hover {
            background: rgba(92, 124, 250, 0.1);
            color: #5C7CFA;
            border-color: #5C7CFA;
        }

        .notes-toggle-btn.active {
            background: rgba(92, 124, 250, 0.15);
            color: #5C7CFA;
            border-color: #5C7CFA;
        }

        /* Exit fullscreen floating button */
        .fullscreen-exit-hint {
            position: fixed;
            top: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 5001;
            display: none;
            backdrop-filter: blur(4px);
            cursor: pointer;
            transition: opacity 0.3s;
        }

        #smart-notes-app.smart-notes-fullscreen~.fullscreen-exit-hint,
        .smart-notes-fullscreen .fullscreen-exit-hint {
            display: block;
        }

        /* Save status toast */
        .save-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            z-index: 6000;
            display: none;
            animation: slideUp 0.3s ease;
        }

        .save-toast.error {
            background: rgba(229, 57, 53, 0.9);
            color: #fff;
        }

        .save-toast.success {
            background: rgba(76, 175, 80, 0.9);
            color: #fff;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* ── LIGHT MODE: study page overrides ── */
        body.light-mode {
            background: #f0f4ff !important;
            color: #1a1d2e !important;
        }

        body.light-mode .home-section {
            background: #f0f4ff !important;
        }

        /* Calendar light vars */
        body.light-mode {
            --cal-bg: #f0f4ff;
            --cal-cell: #ffffff;
            --cal-text: #1a1d2e;
            --cal-muted: #6b7280;
            --cal-border: #d1d9f0;
        }

        body.light-mode .week-header,
        body.light-mode .week-time-gutter {
            background: #f0f4ff !important;
        }

        body.light-mode .week-day-column:hover {
            background: rgba(92, 124, 250, 0.07) !important;
        }

        body.light-mode .view-toggle {
            background: #d1d9f0;
        }

        body.light-mode .view-btn {
            color: #6b7280;
        }

        body.light-mode .view-btn:hover {
            color: #1a1d2e;
        }

        /* Content cards on study page */
        body.light-mode .card,
        body.light-mode [class*="card"] {
            background: #fff;
            border-color: rgba(0, 0, 0, 0.07);
            color: #1a1d2e;
        }

        body.light-mode .card-header {
            background: #fff;
            border-color: rgba(0, 0, 0, 0.07);
        }

        body.light-mode .calendar-section,
        body.light-mode .study-main {
            background: #f0f4ff;
        }

        /* Loader light mode */
        body.light-mode #global-loader {
            background-color: #f0f4ff !important;
        }
    </style>
</head>

<body>
    <div id="global-loader"
        style="position:fixed; top:0; left:0; width:100%; height:100%; background-color:#1a1b21; z-index:9999; display:flex; align-items:center; justify-content:center; transition:opacity 0.5s ease;">
        <div class="spinner-border text-primary" role="status"
            style="width: 3rem; height: 3rem; color: #5C7CFA !important;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo-details">
                <img src="{{ asset('images/mantra.png') }}" alt="Mantra Logo"
                    style="width:36px; height:36px; object-fit:contain; border-radius:8px; flex-shrink:0;">
                <div class="logo_name">MANTRA</div>
                <i class="fa fa-bars" id="btn"></i>
            </div>
            <ul class="nav-list">

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-th-large"></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>
                <li>
                    <a href="{{ route('library') }}">
                        <i class="fa fa-folder-open"></i>
                        <span class="links_name">Library</span>
                    </a>
                    <span class="tooltip">Library</span>
                </li>
                <li>
                    <a href="{{ route('study') }}" class="active">
                        <i class="fa fa-check-square"></i>
                        <span class="links_name">Study Space</span>
                    </a>
                    <span class="tooltip">Study</span>
                </li>
                <li>
                    <a href="{{ route('progress') }}">
                        <i class="fa fa-pie-chart"></i>
                        <span class="links_name">Progress</span>
                    </a>
                    <span class="tooltip">Progress</span>
                </li>
                <li>
                    <a href="{{ route('chat') }}">
                        <i class="fa fa-comments"></i>
                        <span class="links_name">Chat</span>
                    </a>
                    <span class="tooltip">Chat</span>
                </li>
                <li>
                    <a href="{{ route('settings') }}">
                        <i class="fa fa-cog"></i>
                        <span class="links_name">Settings</span>
                    </a>
                    <span class="tooltip">Settings</span>
                </li>
                <li class="profile">
                    <div class="profile-details">
                        <div class="name_job">
                            <div class="name" id="user-name">{{ Auth::user()->name ?? 'Student' }}</div>
                            <div class="job">Learner</div>
                        </div>
                    </div>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" id="logout-btn"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <section class="home-section" style="padding: 0; min-height: 100vh; overflow-y: auto;">
            <div class="calendar-layout">
                <!-- Left Sidebar -->
                <aside class="cal-sidebar">
                    <!-- Mini Calendar -->
                    <div class="mini-cal">
                        <div class="mini-cal-header">
                            <h4 id="mini-month-year">Feb 2026</h4>
                            <div>
                                <span class="btn-text" id="mini-prev"><i class="fa fa-chevron-left"
                                        style="font-size: 10px;"></i></span>
                                <span class="btn-text" id="mini-next"><i class="fa fa-chevron-right"
                                        style="font-size: 10px;"></i></span>
                            </div>
                        </div>
                        <div class="mini-grid" id="mini-grid">
                            <div class="mini-day-name">S</div>
                            <div class="mini-day-name">M</div>
                            <div class="mini-day-name">T</div>
                            <div class="mini-day-name">W</div>
                            <div class="mini-day-name">T</div>
                            <div class="mini-day-name">F</div>
                            <div class="mini-day-name">S</div>
                            <!-- Mini days JS -->
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="cal-filters">
                        <h5>My Calendars</h5>
                        <div class="filter-item" data-type="study">
                            <input type="checkbox" checked>
                            <div class="filter-color" style="background: #5C7CFA;"></div>
                            <span>Study Sessions</span>
                        </div>
                        <div class="filter-item" data-type="exam">
                            <input type="checkbox" checked>
                            <div class="filter-color" style="background: #E53935;"></div>
                            <span>Exams / Deadlines</span>
                        </div>
                        <div class="filter-item" data-type="review">
                            <input type="checkbox" checked>
                            <div class="filter-color" style="background: #FBC02D;"></div>
                            <span>Reviews</span>
                        </div>
                        <div class="filter-item" data-type="meeting">
                            <input type="checkbox" checked>
                            <div class="filter-color" style="background: #8E6CEF;"></div>
                            <span>Personal / Meetings</span>
                        </div>
                    </div>

                    <!-- Tasks Inbox -->
                    <!-- Inbox (Undated Tasks) -->
                    <div class="task-sidebar"
                        style="flex: 1; overflow-y: auto; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h5 style="margin: 0; color: #8A8F98; font-size: 12px; text-transform: uppercase;">Inbox
                            </h5>
                            <i class="fa fa-plus" onclick="dashboard.openModal(null, null)"
                                style="cursor: pointer; font-size: 12px; color: var(--text-muted);"
                                title="Add Task"></i>
                        </div>
                        <div id="inbox-list" class="inbox-list" style="min-height: 50px;">
                            <!-- Undated events loaded here -->
                        </div>
                    </div>
                </aside>

                <!-- Main Calendar Area -->
                <main class="cal-main">
                    <header class="cal-topbar">
                        <div class="cal-title"
                            style="position: relative; align-items: center; display: flex; gap: 8px;">
                            <h2 id="main-month-year" style="cursor: pointer; margin: 0;">February 2026</h2>
                            <i class="fa fa-chevron-down" id="calendar-picker-icon"
                                style="font-size: 14px; color: #8A8F98; cursor: pointer;"></i>

                            <!-- Custom Date Jumper Popup -->
                            <div id="date-jumper"
                                style="display: none; position: absolute; top: 120%; left: 0; background: #2A2B30; padding: 12px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.5); z-index: 100; border: 1px solid #3A3B40; display: flex; gap: 8px; align-items: center;">
                                <select id="jump-month"
                                    style="background: #1E1F23; border: 1px solid #3A3B40; color: #fff; padding: 6px 10px; border-radius: 6px; outline: none; font-size: 13px; -webkit-appearance: none; cursor: pointer;">
                                    <option value="0">Jan</option>
                                    <option value="1">Feb</option>
                                    <option value="2">Mar</option>
                                    <option value="3">Apr</option>
                                    <option value="4">May</option>
                                    <option value="5">Jun</option>
                                    <option value="6">Jul</option>
                                    <option value="7">Aug</option>
                                    <option value="8">Sep</option>
                                    <option value="9">Oct</option>
                                    <option value="10">Nov</option>
                                    <option value="11">Dec</option>
                                </select>
                                <input type="number" id="jump-year" placeholder="Year"
                                    style="background: #1E1F23; border: 1px solid #3A3B40; color: #fff; padding: 6px 10px; border-radius: 6px; width: 60px; font-size: 13px; outline: none;">
                                <button id="jump-btn"
                                    style="background: transparent; border: 1px solid #5C7CFA; color: #ffffff; padding: 6px 16px; border-radius: 20px; cursor: pointer; font-size: 13px; transition: 0.2s;">Go</button>
                            </div>
                        </div>
                        <div class="cal-controls">
                            <!-- View Toggle -->
                            <div class="view-toggle">
                                <button class="view-btn active" data-view="month">Month</button>
                                <button class="view-btn" data-view="week">Week</button>
                                <button class="view-btn" data-view="day">Day</button>
                                <button class="view-btn" data-view="year">Year</button>
                            </div>
                            <button class="cal-nav-btn" id="main-prev"><i class="fa fa-chevron-left"></i></button>
                            <button class="cal-nav-btn" id="main-today">Today</button>
                            <button class="cal-nav-btn" id="main-next"><i class="fa fa-chevron-right"></i></button>
                            <button class="cal-nav-btn" id="create-event-main"
                                style="background: var(--cal-study); border: none; font-weight: 600;">+ New</button>
                        </div>
                    </header>

                    <!-- Month View -->
                    <div id="month-view">
                        <div class="main-grid-header">
                            <div class="day-header">Sun</div>
                            <div class="day-header">Mon</div>
                            <div class="day-header">Tue</div>
                            <div class="day-header">Wed</div>
                            <div class="day-header">Thu</div>
                            <div class="day-header">Fri</div>
                            <div class="day-header">Sat</div>
                        </div>

                        <div class="main-grid-body" id="main-calendar-grid">
                            <!-- Grid Cells JS -->
                        </div>
                    </div>
                    <!-- Year View -->
                    <div id="year-view" style="display: none; padding: 20px;">
                        <div class="year-grid" id="year-grid"
                            style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                            <!-- Months rendered by JS -->
                        </div>
                    </div>

                    <!-- Week View -->
                    <div id="week-view" style="display: none;">
                        <div class="week-grid-container">
                            <div class="week-header">
                                <div class="week-time-gutter"></div>
                                <div class="week-day-header" data-index="0"><span class="day-name">Sun</span><span
                                        class="day-num" id="wh-0"></span></div>
                                <div class="week-day-header" data-index="1"><span class="day-name">Mon</span><span
                                        class="day-num" id="wh-1"></span></div>
                                <div class="week-day-header" data-index="2"><span class="day-name">Tue</span><span
                                        class="day-num" id="wh-2"></span></div>
                                <div class="week-day-header" data-index="3"><span class="day-name">Wed</span><span
                                        class="day-num" id="wh-3"></span></div>
                                <div class="week-day-header" data-index="4"><span class="day-name">Thu</span><span
                                        class="day-num" id="wh-4"></span></div>
                                <div class="week-day-header" data-index="5"><span class="day-name">Fri</span><span
                                        class="day-num" id="wh-5"></span></div>
                                <div class="week-day-header" data-index="6"><span class="day-name">Sat</span><span
                                        class="day-num" id="wh-6"></span></div>
                            </div>
                            <div class="week-grid" id="week-grid">
                                <!-- Time slots rendered by JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Day View -->
                    <div id="day-view" style="display: none; height: 100%; flex-direction: column;">
                        <!-- Important / Untimed Section -->
                        <div id="day-untimed-section"
                            style="padding: 15px; border-bottom: 1px solid var(--cal-border); background: var(--cal-bg); flex-shrink: 0;">
                            <h6
                                style="margin: 0 0 10px 0; color: #8A8F98; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Important / All Day</h6>
                            <div id="day-untimed-list" style="display: flex; flex-direction: column; gap: 5px;">
                                <!-- JS items -->
                            </div>
                        </div>

                        <div class="week-grid-container" style="flex: 1; overflow: visible;">
                            <div class="day-header-wrapper"
                                style="display: grid; grid-template-columns: 60px 1fr; position: sticky; top: 0; z-index: 10; background: var(--cal-bg); border-bottom: 1px solid var(--cal-border);">
                                <div class="week-time-gutter"></div>
                                <div class="week-day-header"
                                    style="border-left: 1px solid var(--cal-border); width: 100%;">
                                    <span class="day-name" id="day-view-name"></span>
                                    <span class="day-num" id="day-view-num"></span>
                                </div>
                            </div>
                            <div class="day-grid" id="day-grid"
                                style="display: grid; grid-template-columns: 60px 1fr; position: relative;">
                                <!-- Time slots rendered by JS -->
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            <!-- Smart Notes Section (Notion Style) -->
            <div id="smart-notes-app" class="row mt-4">
                <div class="col-md-12">
                    <div class="card" style="min-height: 85vh; border: none; border-radius: 12px; overflow: hidden;">
                        <div class="row no-gutters h-100">
                            <!-- Sidebar -->
                            <div class="col-md-3 notes-sidebar-col" style="height: 100%; overflow-y: auto;">
                                <div class="p-3">
                                    <h5 class="text-muted text-uppercase font-weight-bold mb-3"
                                        style="font-size: 0.8rem; letter-spacing: 1px;">My Notes</h5>

                                    <div class="nav flex-column nav-pills" id="notes-sidebar-menu" role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link active d-flex align-items-center" href="#" data-filter="all">
                                            <i class="fa fa-sticky-note-o mr-2"></i> All Notes
                                        </a>
                                        <a class="nav-link d-flex align-items-center" href="#" data-filter="pinned">
                                            <i class="fa fa-star mr-2 text-warning"></i> Favorites
                                        </a>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-between align-items-center">
                                        <h5 class="text-muted text-uppercase font-weight-bold mb-0"
                                            style="font-size: 0.8rem; letter-spacing: 1px;">Folders</h5>
                                        <button class="btn btn-sm btn-link p-0 text-muted"
                                            onclick="SmartNotes.createFolder()"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="nav flex-column nav-pills mt-2" id="folder-list">
                                        <!-- Folders injected via JS -->
                                    </div>

                                    <div class="mt-4">
                                        <h5 class="text-muted text-uppercase font-weight-bold mb-2"
                                            style="font-size: 0.8rem; letter-spacing: 1px;">Tags</h5>
                                        <div id="tags-list" class="d-flex flex-wrap">
                                            <!-- Tags injected via JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Editor Area -->
                            <div class="col-md-3 notes-list-col" style="height: 100%; overflow-y: auto;"
                                id="notes-list-column">
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold" id="current-view-title">All Notes</h6>
                                    <button class="btn btn-primary btn-sm rounded-circle shadow-sm"
                                        onclick="SmartNotes.createNote()">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="p-2">
                                    <input type="text" class="form-control form-control-sm bg-light border-0"
                                        placeholder="Search notes..." id="search-notes">
                                </div>
                                <div class="list-group list-group-flush" id="notes-list">
                                    <!-- Note Items injected via JS -->
                                </div>
                            </div>

                            <!-- Editor -->
                            <div class="col-md-6 notes-editor-col"
                                style="height: 100%; display: flex; flex-direction: column; overflow-y: auto;">



                                <div id="note-editor-container"
                                    style="display: none; min-height: 100%; flex-direction: column;">
                                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                                        <input type="text" id="note-title-input"
                                            class="form-control border-0 font-weight-bold"
                                            style="font-size: 1.5rem; background: transparent; box-shadow: none;"
                                            placeholder="Untitled">
                                        <div class="d-flex align-items-center">
                                            <select id="note-folder-select" class="custom-select custom-select-sm mr-2"
                                                style="width: 120px; border: none; background: #f8f9fa; color: #6c757d;">
                                                <option value="">No Folder</option>
                                                <!-- Folders via JS -->
                                            </select>
                                            <span id="save-status" class="text-muted small mr-3">Saved</span>

                                            <button class="notes-toggle-btn mr-1" onclick="SmartNotes.toggleSidebar()"
                                                title="Toggle Sidebar">
                                                <i class="fa fa-columns"></i>
                                            </button>
                                            <button class="notes-toggle-btn mr-1"
                                                onclick="SmartNotes.toggleFullscreen()" title="Fullscreen">
                                                <i class="fa fa-expand" id="fullscreen-icon"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning border-0"
                                                onclick="SmartNotes.togglePin()" title="Pin Note"><i
                                                    class="fa fa-star-o" id="pin-icon"></i></button>
                                            <button class="btn btn-sm btn-outline-danger border-0 ml-1"
                                                onclick="SmartNotes.deleteCurrentNote()" title="Delete"><i
                                                    class="fa fa-trash-o"></i></button>
                                        </div>
                                    </div>
                                    <div id="quill-editor" style="flex: 1; border: none; padding: 20px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fullscreen Exit Hint -->
            <div class="fullscreen-exit-hint" onclick="SmartNotes.toggleFullscreen()">
                <i class="fa fa-compress"></i> Press ESC or click to exit fullscreen
            </div>
            <!-- Save Toast -->
            <div id="save-toast" class="save-toast"></div>
            <!-- End Smart Notes Section -->

            <!-- Editor.js and Plugins (Local) -->
            <script src="{{ asset('js/editorjs/editorjs.js') }}"></script>
            <script src="{{ asset('js/editorjs/header.js') }}"></script>
            <script src="{{ asset('js/editorjs/nested-list.js') }}"></script>
            <script src="{{ asset('js/editorjs/checklist.js') }}"></script>
            <script src="{{ asset('js/editorjs/quote.js') }}"></script>
            <script src="{{ asset('js/editorjs/code.js') }}"></script>
            <script src="{{ asset('js/editorjs/marker.js') }}"></script>
            <script src="{{ asset('js/editorjs/inline-code.js') }}"></script>
            <script src="{{ asset('js/editorjs/delimiter.js') }}"></script>
            <script src="{{ asset('js/editorjs/underline.js') }}"></script>
            <script src="{{ asset('js/editorjs/table.js') }}"></script>
            <script src="{{ asset('js/editorjs/text-color.js') }}"></script>

        </section>

        <!-- Enhanced Event Modal -->
        <div id="event-modal-overlay"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 3000; justify-content: center; align-items: center;">
            <div class="event-modal-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h4 style="margin: 0;" id="modal-title">New Event</h4>
                    <i class="fa fa-times" id="close-event-modal" style="cursor: pointer; color: var(--cal-muted);"></i>
                </div>

                <input type="hidden" id="event-id">

                <input type="text" id="event-title" placeholder="Event title..." class="form-control-dark mb-3"
                    style="font-size: 16px; font-weight: 500;">

                <textarea id="event-description" placeholder="Add description..." class="form-control-dark mb-3"
                    rows="2" style="resize: none;"></textarea>

                <!-- Color Picker -->
                <label
                    style="font-size: 12px; color: var(--cal-muted); margin-bottom: 8px; display: block;">Color</label>
                <div class="color-picker-row" id="color-picker">
                    <div class="color-option selected" data-color="#5C7CFA" style="background: #5C7CFA;"></div>
                    <div class="color-option" data-color="#4CAF50" style="background: #4CAF50;"></div>
                    <div class="color-option" data-color="#FBC02D" style="background: #FBC02D;"></div>
                    <div class="color-option" data-color="#E53935" style="background: #E53935;"></div>
                    <div class="color-option" data-color="#8E6CEF" style="background: #8E6CEF;"></div>
                    <div class="color-option" data-color="#8A8F98" style="background: #8A8F98;"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Type</label>
                        <select id="event-type" class="form-control-dark">
                            <option value="study">📘 Study</option>
                            <option value="exam">🔴 Exam / Deadline</option>
                            <option value="review">📝 Review</option>
                            <option value="meeting">👤 Personal</option>
                            <option value="birthday">🎂 Birthday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Priority</label>
                        <select id="event-priority" class="form-control-dark">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Date</label>
                        <input type="text" id="event-date-input" class="form-control-dark" placeholder="DD-MM-YYYY">
                    </div>
                    <div class="form-group" style="flex: 0.8;">
                        <label for="event-time-input">Start Time</label>
                        <input type="time" id="event-time-input" class="form-control-dark">
                    </div>
                    <div class="form-group" style="flex: 0.8;">
                        <label for="event-end-time-input">End Time</label>
                        <input type="time" id="event-end-time-input" class="form-control-dark">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="event-reminder">Reminder</label>
                        <select id="event-reminder" class="form-control-dark">
                            <option value="none">No reminder</option>
                            <option value="at_time">At time of event</option>
                            <option value="5min">5 minutes before</option>
                            <option value="10min">10 minutes before</option>
                            <option value="1hour">1 hour before</option>
                            <option value="1day">1 day before</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="event-recurrence">Repeat</label>
                        <select id="event-recurrence" class="form-control-dark">
                            <option value="">No repeat</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button id="delete-event-btn" class="btn-secondary-small" style="flex: 1; display: none;">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                    <button id="save-event-btn" class="btn-primary-small" style="flex: 2;">
                        Create Event
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Selection Action Bar -->
    <div id="selection-action-bar"
        style="display: none; position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: #2C3038; padding: 10px 20px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 4000; align-items: center; gap: 20px; border: 1px solid rgba(255,255,255,0.1);">
        <button id="cancel-selection"
            style="background: transparent; border: none; color: #8A8F98; cursor: pointer; font-size: 14px; font-weight: 500;">Cancel</button>
        <span id="selection-count" style="color: #fff; font-size: 14px; font-weight: 600;">0 Selected</span>
        <button id="delete-selection"
            style="background: #E53935; border: none; color: #fff; padding: 6px 16px; border-radius: 20px; cursor: pointer; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
            <i class='bx bx-trash'></i> Delete
        </button>
    </div>


    <script>
        // Color picker functionality
        document.querySelectorAll('.color-option').forEach(opt => {
            opt.addEventListener('click', function () {
                document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Type change updates color
        document.getElementById('event-type').addEventListener('change', function () {
            const typeColors = {
                'study': '#5C7CFA',
                'exam': '#E53935',
                'review': '#FBC02D',
                'meeting': '#8E6CEF',
                'birthday': '#FBC02D'
            };
            const color = typeColors[this.value] || '#5C7CFA';
            document.querySelectorAll('.color-option').forEach(o => {
                o.classList.toggle('selected', o.dataset.color === color);
            });
        });
    </script>

    <!-- Lo-Fi Player Widget -->
    <div class="music-widget closed">
        <div class="music-controls">
            <div class="music-art">
                <img src="{{ asset('images/mantra.png') }}" alt="Album Art">
            </div>
            <div class="track-info">
                <span class="track-name">Chill Study Beats ☕</span>
                <span class="artist-name">Mantra Radio</span>
                <div class="progress-bar-music">
                    <div class="fill"></div>
                    <div class="seek-thumb"></div>
                </div>
            </div>
            <div class="control-btns">
                <button class="m-btn"><i class="fa fa-step-backward"></i></button>
                <button class="m-btn play-pause"><i class="fa fa-play"></i></button>
                <button class="m-btn"><i class="fa fa-step-forward"></i></button>
            </div>
        </div>
        <div class="music-toggle" title="Toggle Lo-Fi Radio">
            <i class="fa fa-music"></i>
            <div class="equalizer">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>

    <!-- Floating Study Timer -->
    <div id="floating-study-timer"
        style="position: fixed; bottom: 110px; right: 20px; border-radius: 50px; padding: 10px 20px; background: linear-gradient(135deg, #5C7CFA, #4B6BF5); color: white; display: none; align-items: center; gap: 10px; box-shadow: 0 10px 25px rgba(92, 124, 250, 0.4); z-index: 5000; transition: all 0.3s ease; cursor: pointer;"
        title="Active Study Session">
        <i class="fa fa-clock-o" style="font-size: 20px;"></i>
        <span id="floating-timer-time"
            style="font-size: 18px; font-weight: 700; font-family: 'Poppins', sans-serif; letter-spacing: 1px;">25:00</span>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}?v=11"></script>
    <!-- Music player loads AFTER dashboard.js to override its MusicPlayer -->
    <script src="{{ asset('js/music-player.js') }}?v={{ time() }}"></script>
    <script>
        // Hide global loader
        $(document).ready(function () {
            const loader = document.getElementById('global-loader');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }, 500);
            }

        });
    </script>
    <script>
        (function () {
            if (localStorage.getItem('mantra_pref_dark') === '0') document.body.classList.add('light-mode');
            if (localStorage.getItem('mantra_pref_compact') === '1') {
                var sb = document.querySelector('.sidebar');
                if (sb) sb.classList.add('compact');
            }
        })();
    </script>
</body>

</html>