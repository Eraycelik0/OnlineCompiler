<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Online Compiler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Online Compiler</h1>
    <div class="form-group">
        @csrf
        <label for="language">Select Language:</label>
        <select class="form-control" id="language">
            <option value="python">Python</option>
            <option value="java">Java</option>
            <option value="cpp">C++</option>
            <option value="php">PHP</option>
            <option value="javascript">JavaScript</option>
        </select>
    </div>
    <div class="form-group">
        <label for="code">Enter Your Code:</label>
        <textarea class="form-control" id="code" rows="10"></textarea>
    </div>
    <button class="btn btn-primary" id="compileBtn">Compile & Run</button>
    <div class="mt-4">
        <h3>Output:</h3>
        <pre id="output"></pre>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#compileBtn").click(function() {
            var code = $("#code").val();
            var language = $("#language").val();
            var url = "{{ route('compile') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    code: code,
                    language: language
                },
                success: function(response) {
                    $("#output").text(response.output);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Dil seçildiğinde, dilin hazır kod şablonunu göstermek için
        $("#language").change(function() {
            var language = $(this).val();
            var code = getLanguageCodeTemplate(language);
            $("#code").val(code);
        });

        // Dilin hazır kod şablonunu alalım
        function getLanguageCodeTemplate(language) {
            switch (language) {
                case 'python':
                    return 'print("Hello, World!");';
                case 'java':
                    return 'public class Main {\n    public static void main(String[] args) {\n        System.out.println("Hello, World!");\n    }\n}';
                case 'cpp':
                    return '#include <iostream>\n\nint main() {\n    std::cout << "Hello, World!" << std::endl;\n    return 0;\n}';
                case 'php':
                    return '<?php echo "Hello, World!"; ?>';
                case 'javascript':
                    return 'console.log("Hello, World!");';
                default:
                    return '';
            }
        }
    });
</script>
</body>
</html>
