<style>
    body {
        display: grid;
        background-color: #eaedf2;
        justify-content: center;
        align-items: center;

        font: {
            family: "Nunito", sans-serif;
            size: 14px;
        }

    }
    .header {
        height: 50px;
        padding: 10px 10px 0;
        background: #D3D3D3;
    }
    footer {
        background: #D3D3D3;
        color: black;
    }

    a {
        color: #00aaff;
        margin-left: auto;
        margin-right: auto;
    }

    .flex-box {
        display: flex;
        top: 20%;
    }

    .flex-item1 {
        width: 400px;
        height: 400px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .flex-item2 {
        width: 610px;
        height: 800px;
        margin-left: 10px;
        margin-right: 10px;
    }

    table.chart {
        border: 1px solid #1C6EA4;
        background-color: #EEEEEE;
        width: 36%;
        height: 250px;
        text-align: center;
    }

    table.chart td,
    table.chart th {
        border: 1px solid #D3D3D3;
        padding: 1px 2px;
    }

    table.chart tbody td {
        font-size: 14px;
        font-weight: bold;
    }

    table.chart tfoot td {
        font-size: 14px;
    }

    form {
        display: flex;
        flex-wrap: wrap;
    }

    .block {
        position: absolute;
        top: 12.5%;
        left: 30%;
        margin: -125px 0 0 -125px;
    }

    input[type="date"] {
        background-color: white;
        outline: none;
    }

    input[type="date"]::-webkit-clear-button {
        font-size: 18px;
        height: 30px;
        position: relative;
    }

    input[type="date"]::-webkit-inner-spin-button {
        height: 28px;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        font-size: 15px;
    }

    input[type="date"] {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
        margin: 0;
    }

    label {
        display: block;
    }

    input {
        border: 1px solid #c4c4c4;
        border-radius: 5px;
        background-color: #fff;
        padding: 3px 5px;
        box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.1);
        width: 190px;
    }

    p {
        font-size: 13pt;

        margin-left: auto;
        margin-right: auto;
        margin-top: 15px;
        text-align: center;
    }

    .center {
        text-align: center;
    }

    .main {
        background: white;
        border-radius: 10px;
    }
</style>