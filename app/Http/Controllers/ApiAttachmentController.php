<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cardId = \Input::get('card_id');

        $card = \App\Card::whereId($cardId)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        if (! $card) {
            return abort(404);
        }

        $file = $request->file('file');
        $ext = $file->guessExtension();
        $filename = date('j-m-y') . '-' . str_random(100) . '.' . $ext;
        $filepath = storage_path('app') . '/' . $filename;
        $request->file('file')->move(storage_path('app'), $filename);
        $original_filename = $file->getClientOriginalName();
        $file_size = $file->getClientSize();

        $s3 = \App::make('aws')->createClient('s3');
        $success = $s3->putObject(array(
            'Bucket'     => env('S3_BUCKET_ATTACHMENTS_NAME'),
            'Key'        => $filename,
            'SourceFile' => $filepath,
            'ACL'        => 'public-read'
        ));

        unlink($filepath);

        $attachment = \App\Attachment::create([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->team_id,
            'card_id' => $card->id,
            'filename' => $filename,
            'file_url' => env('S3_BUCKET_ATTACHMENTS_URL') . $filename,
            'file_size' => $file_size,
            'original_filename' => $original_filename
        ]);

        return response()->json([
            'attachment' => $attachment,
            'success' => (bool) $attachment
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $attachment = \App\Attachment::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        if (! $attachment) abort('404');

        $s3 = \App::make('aws')->createClient('s3');
        $success = $s3->deleteObject(array(
            'Bucket'     => env('S3_BUCKET_ATTACHMENTS_NAME'),
            'Key'        => $attachment->filename,
        ));

        $success = $attachment->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
