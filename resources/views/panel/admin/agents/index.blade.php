@extends('layouts.main')
@section('title', 'Agents')
@section('content')
    @php
        /**
         * Item
         *
         * @category Hq.ai
         *
         * @ref zCURD
         * @author  Defenzelite <hq@defenzelite.com>
         * @license https://www.defenzelite.com Defenzelite Private Limited
         * @version <Hq.ai: 1.1.0>
         * @link    https://www.defenzelite.com
         */
        //    return  $category =App\Models\Category::where('category_id',request()->get('category'))->first();
        $breadcrumb_arr = [['name' => 'Agents', 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <style>
            .featured-icon i {
                font-size: 14px;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Agents </h5>
                            <span>@lang('admin/ui.list_of') Agents</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Agents</h3>
                        <span class="font-weight-bold border-bottom trash-option   d-none ">Trash</span>
                        <div class="d-flex justicy-content-right">
                            @if ($permissions->contains('add_content'))
                                <a href="{{ route('panel.admin.agents.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Content">
                                    <i class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add')
                                </a>
                            @endif
                           
                        </div>
                    </div>
                    <div id="ajax-container">
                        @include('panel.admin.agents.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.agents.include.filter')

    <!-- push external js -->
    @push('script')
        @include('panel.admin.include.bulk-script')
        {{-- START HTML TO EXCEL BUTTON INIT --}}
        <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

        <script>
            $(document).ready(function() {
                // $('#department_id').trigger('change');
            });

            function html_table_to_excel(type) {
                var table_core = $("#table").clone();
                var clonedTable = $("#table").clone();
                clonedTable.find('[class*="no-export"]').remove();
                clonedTable.find('[class*="d-none"]').remove();
                $("#table").html(clonedTable.html());

                // Use in reverse format beacuse we are prepending it.
                var report_format = [{
                        'label': "Category",
                        'value': "{{ $category->name ?? 'All Category' }}"
                    },
                    {
                        'label': "Date Range",
                        'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                    },
                    {
                        'label': "Report Name",
                        'value': "Agent Report"
                    },
                    {
                        'label': "Company",
                        'value': "{{ env('APP_NAME') }}"
                    }
                ];

                var report_name = report_format[2]['value'] + " | " + Date.now();
                // Create a single blank row
                var blankRow = document.createElement('tr');
                var blankCell = document.createElement('th');
                blankCell.colSpan = clonedTable.find('thead tr th').length;
                blankRow.appendChild(blankCell);

                // Append the blank row to the cloned table's thead
                clonedTable.find('thead').prepend(blankRow);

                // Iterate through the report_format array and add metadata rows to the cloned table's thead
                $.each(report_format, function(index, item) {
                    var metadataRow = document.createElement('tr');
                    var labelCell = document.createElement('th');
                    var valueCell = document.createElement('th');

                    labelCell.innerHTML = item.label;
                    valueCell.innerHTML = item.value;

                    metadataRow.appendChild(labelCell);
                    metadataRow.appendChild(valueCell);

                    clonedTable.find('thead').prepend(metadataRow);
                });

                var data = clonedTable[0]; // Use the cloned table for export

                var file = XLSX.utils.table_to_book(data, {
                    sheet: "sheet1"
                });

                // Write and download the Excel file
                XLSX.write(file, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                });
                XLSX.writeFile(file, report_name + '.' + type);

                $("#table").html(table_core.html());
            }

            $(document).on('click', '#export_button', function() {
                html_table_to_excel('xlsx');
            });
        </script>
        <script>
            $('#reset').click(function() {
                fetchData("{{ route('panel.admin.agents.index') }}");
                window.history.pushState("", "", "{{ route('panel.admin.agents.index') }}");
                $('#TableForm').trigger("reset");
                $(document).find('.close.off-canvas').trigger('click');
            });
        </script>
        <script>
            //    const apiKey = '{{env('CHATGPT_API_KEY')}}';
            const apiKey = '{{ getSetting('gpt_api_key') }}';


            $('#speak_answer').on('click', function() {
                var recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition.lang = 'en-US';
                recognition.interimResults = false;

                recognition.onstart = function() {
                    $('#speak_answer').html('<i class="fa fa-spinner fa-spin"></i>');
                };

                recognition.onresult = function(event) {
                    const speechResult = event.results[0][0].transcript;
                    // alert(speechResult);
                    $('#content').val(speechResult);
                    $('#send').click(); // Trigger the existing text-based chat function
                };

                recognition.onerror = function(event) {
                    console.error(event.error);
                    pushNotification('error', 'Speech recognition error: ' + event.error);
                };

                recognition.onend = function() {
                    $('#speak_answer').html('<i class="fa fa-microphone"></i>');
                };

                recognition.start();
            });

            function speakText(text) {
                var speechSynthesis = window.speechSynthesis;
                var speech = new SpeechSynthesisUtterance(text);
                speech.lang = 'en-US';
                speech.pitch = 1;
                speech.rate = 1;
                speechSynthesis.speak(speech);
            }

            async function chatSequence(content) {
                appendElement('chat-item', 'Typing...', 'assistant', 'bg_light');
                var data = {
                    model: "gpt-3.5-turbo",
                    messages: [{ role: "user", content: content }],
                    stream: true, // Enable streaming
                };

                const response = await fetch('https://api.openai.com/v1/chat/completions', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${apiKey}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.body) {
                    pushNotification('error', 'ReadableStream not supported');
                    return;
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder('utf-8');
                let text = '';
                let typingElement = $('.chat-item:contains("Typing...")').last();

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value);
                    const lines = chunk.split('\n');
                    for (let line of lines) {
                        if (line.trim() === '') continue;
                        if (line.trim() === 'data: [DONE]') {
                            typingElement.remove();
                            appendElement('chat-item', text, 'assistant', 'bg_light', true); // Show the Speak button
                            
                            $('#content').css('pointer-events', 'auto');
                            $('#content').css('background', 'white');
                            $('#send').prop('disabled', false);
                            $('#send').html('<i class="fa fa-paper-plane"></i>');
                            return;
                        }

                        try {
                            const json = JSON.parse(line.replace(/^data: /, ''));
                            const delta = json.choices[0].delta;
                            if (delta.content) {
                                text += delta.content;
                                typingElement.find('.chat-content .box').html(text.replace(/\n/g, '<br>'));
                                $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
                            }
                        } catch (error) {
                            console.error('Error parsing line:', line, error);
                        }
                    }
                }
            }

            $('#send').on('click', async function(e) {
                var content = $('#content').val();
                if (content == "") {
                    pushNotification('error', 'Please enter content', 'error');
                    return false;
                } else {
                    // Preparation steps before regeneration or initial generation
                    $('#blank_message').removeClass('d-flex').addClass('d-none');
                    $('.chat-list').show();
                    $('#content').val('');
                    $('#send').prop('disabled', true);
                    $('#content').css('pointer-events', 'none');
                    $('#content').css('background', 'aliceblue');
                    $('#send').html('<i class="fa fa-spinner fa-spin"></i>');
                    appendElement('chat-item', content, 'user',true);

                    // Start the chat sequence with the given content
                    await chatSequence(content);
                }
            });
            let messageIndex = 0; // Initialize a global index counter

            function appendElement(chat_list, message, by, bg_light = null, showSpeakButton = false) {
                new_chat_list = chat_list;
                if (by == 'user') {
                    new_chat_list = chat_list + ' odd';
                }
                const currentIndex = messageIndex++;

                var speakButtonHtml = showSpeakButton ? 
                    '<button class="btn btn-icon btn-dark text-white mt-1 p-0 chat-box-send ml-1" onclick="handleSpeakButtonClick(' + currentIndex + ')"><i class="fa fa-play"></i></button>' : '';
                var newListItem = $('<li id="message-' + currentIndex + '" class="' + new_chat_list + ' mt-1">' +
                        '<div class="pl-0 chat-content ' + bg_light + '">' +
                            '<div class="box bg-light-info">' + message + '</div>' +
                            speakButtonHtml +
                        '</div>' +
                    '</li>');
                $('.chat-list').append(newListItem);
                $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
            }

            function handleSpeakButtonClick(currentIndex) {
                var textToSpeak = $('#message-'+currentIndex).html();
                if (textToSpeak) {
                    speakText(textToSpeak);
                }
            }

            // Add event listener for dynamically added buttons
            $(document).on('click', '.speak-button', handleSpeakButtonClick);

        </script>

        {{-- END RESET BUTTON INIT --}}
    @endpush
@endsection
