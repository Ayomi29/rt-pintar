<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\CoverLetter;
use App\Models\DashboardNotification;
use App\Models\DataRt;
use App\Models\FamilyMember;
use App\Models\PushNotification;
use App\Models\User;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;
use PhpOffice\PhpWord\TemplateProcessor;

class ApiCoverLetterController extends Controller
{
    function notify($title, $body, $image = null)
    {
        return new PushNotification($title, $body, $image);
    }
    public function index()
    {
        $cover_letter = CoverLetter::with('family_member')->whereHas('family_member', function ($q) {
            $q->whereHas('family_card', function ($q1) {
                $q1->whereHas('house', function ($q2) {
                    $q2->where('id', auth('api')->user()->family_member->family_card->house->id);
                });
            });
        })->orderBy('id', 'desc')->get();

        if (count($cover_letter) < 1) {
            $cover_letter = null;
        }

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data surat pengantar';
        $data = ['cover_letter' => $cover_letter];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function getDataFamily()
    {
        $family_member = FamilyMember::where('family_card_id', auth('api')->user()->family_member->family_card_id)->orderBy('id', 'asc')->get();

        if (count($family_member) < 1) {
            $family_member = null;
        }

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data keluarga';
        $data = ['family_member' => $family_member];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    public function store(Request $request)
    {
        $letter_number_cover_letter = CoverLetter::where('year', Carbon::now(8)->format('Y'))->orderBy('id', 'desc')->pluck('letter_number')->first();
        if ($letter_number_cover_letter > 0) {
            $letter_number = $letter_number_cover_letter;
        }
        if ($letter_number_cover_letter == null) {
            $letter_number = 0;
        }
        $cover_letter = CoverLetter::create([
            'family_member_id' => request('family_member_id'),
            'letter_number' => $letter_number + 1,
            'year' => Carbon::now(8)->format('Y'),
            'description' => request('description'),
            'created_by' => auth('api')->user()->family_member->family_member_name,
            'status' => 'menunggu'
        ]);

        if (request('title') == 'SURAT PENGANTAR (Ahli Waris)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Ahli_Waris).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Akta Kematian)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Akta_Kematian).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Bertempat Tinggal Sementara)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Bertempat_Tinggal_Sementara).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Domisili Bertempat Tinggal)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Domisili_Bertempat_Tinggal).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Domisili Penduduk untuk WNA)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Domisili_Penduduk_untuk_WNA).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Domisili Tempat Ibadah)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Domisili_Tempat_Ibadah).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Domisili Usaha)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Domisili_Usaha).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Domisili Yayasan)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Domisili_Yayasan).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Kuasa Ahli Waris)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Kuasa_Ahli_Waris).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Permohonan Kredit Bank)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Permohonan_Kredit_Bank).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Perubahan Data orang yg sama)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Perubahan_Data_orang_yg_sama).docx';
        }

        if (request('title') == 'SURAT PENGANTAR (Surat Izin Keramaian Pertunjukan)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Surat_Izin_Keramaian_Pertunjukan).docx';
        }
        if (request('title') == 'SURAT PENGANTAR (Surat Ket. Menikah)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Surat_Ket._Menikah).docx';
        }
        if (request('title') == 'SURAT PENGANTAR (Surat Keterangan)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Surat_Keterangan).docx';
        }
        if (request('title') == 'SURAT PENGANTAR (Surat Keterangan Tidak Mampu)') {
            $template = 'storage/template/SURAT_PENGANTAR_(Surat_Keterangan_Tidak_Mampu).docx';
        }
        if (request('title') == 'SURAT PENGANTAR') {
            $template = 'storage/template/SURAT_PENGANTAR.docx';
        }
        if ($template == null) {
            $status = 'error';
            $status_code = 401;
            $message = 'template tidak ditemukan';
            return response()->json(compact('status', 'status_code', 'message'), 401);
        }
        $dataRT = DataRt::get()->first();
        $file_template = public_path($template);
        $document = new TemplateProcessor($file_template);
        $family_member = FamilyMember::where('id', request('family_member_id'))->first();
        $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
        $unChecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
        $document->setValue('rt_name', $dataRT->name_lead_rt);

        if (Carbon::now(8)->format('M') == 'Jan') {
            $bulan = 'Januari';
        }

        if (Carbon::now(8)->format('M') == 'Feb') {
            $bulan = 'Februari';
        }

        if (Carbon::now(8)->format('M') == 'Mar') {
            $bulan = 'Maret';
        }

        if (Carbon::now(8)->format('M') == 'Apr') {
            $bulan = 'April';
        }

        if (Carbon::now(8)->format('M') == 'May') {
            $bulan = 'Mei';
        }

        if (Carbon::now(8)->format('M') == 'Jun') {
            $bulan = 'Juni';
        }

        if (Carbon::now(8)->format('M') == 'Jul') {
            $bulan = 'Juli';
        }

        if (Carbon::now(8)->format('M') == 'Aug') {
            $bulan = 'Agustus';
        }

        if (Carbon::now(8)->format('M') == 'Sep') {
            $bulan = 'September';
        }

        if (Carbon::now(8)->format('M') == 'Oct') {
            $bulan = 'Oktober';
        }

        if (Carbon::now(8)->format('M') == 'Nov') {
            $bulan = 'November';
        }

        if (Carbon::now(8)->format('M') == 'Dec') {
            $bulan = 'Desember';
        }

        $document->setValue('rt_number', 15);
        $document->setValue('year', Carbon::now(8)->format('Y'));
        $document->setValue('date', Carbon::now(8)->format('d') . ' ' . $bulan . ' ' . Carbon::now(8)->format('Y'));
        $document->setValue('family_member_name', $family_member->family_member_name);
        $month = Carbon::now(8)->format('m');
        if ($month == '01') {
            $romawi = 'I';
        }
        if ($month == '02') {
            $romawi = 'II';
        }
        if ($month == '03') {
            $romawi = 'III';
        }
        if ($month == '04') {
            $romawi = 'IV';
        }
        if ($month == '05') {
            $romawi = 'V';
        }
        if ($month == '06') {
            $romawi = 'VI';
        }
        if ($month == '07') {
            $romawi = 'VII';
        }
        if ($month == '08') {
            $romawi = 'VIII';
        }
        if ($month == '09') {
            $romawi = 'IX';
        }
        if ($month == '10') {
            $romawi = 'X';
        }
        if ($month == '11') {
            $romawi = 'XI';
        }
        if ($month == '12') {
            $romawi = 'XII';
        }

        $document->setValue('month', $romawi);
        $document->setValue('letter_number', $cover_letter->letter_number);

        if ($family_member->gender == 'laki-laki') {
            $document->setValue('l', $checked);
        } else {
            $document->setValue('l', $unChecked);
        }

        if ($family_member->gender == 'Perempuan') {
            $document->setValue('p', $checked);
        } else {
            $document->setValue('p', $unChecked);
        }
        $document->setValue('birth_place', $family_member->birth_place);
        $birth_date = explode('-', $family_member->birth_date);
        $document->setValue('birth_date', $birth_date[2] . '-' . $birth_date[1] . '-' . $birth_date[0]);
        if ($family_member->marital_status == 'kawin') {
            $document->setValue('k', $checked);
        } else {
            $document->setValue('k', $unChecked);
        }

        if ($family_member->marital_status == 'belum kawin') {
            $document->setValue('bk', $checked);
        } else {
            $document->setValue('bk', $unChecked);
        }

        if ($family_member->marital_status == 'duda\janda') {
            $document->setValue('j/d', $checked);
        } else {
            $document->setValue('j/d', $unChecked);
        }

        if ($family_member->citizenship == 'wni') {
            $document->setValue('citizenship', 'WNI');
        } else {
            $document->setValue('citizenship', 'WNA');
        }

        if ($family_member->religion == 'islam') {
            $document->setValue('religion', 'Islam');
        }

        if ($family_member->religion == 'kristen') {
            $document->setValue('religion', 'Kristen');
        }

        if ($family_member->religion == 'katholik') {
            $document->setValue('religion', 'Katholik');
        }
        if ($family_member->religion == 'hindu') {
            $document->setValue('religion', 'Hindu');
        }
        if ($family_member->religion == 'budha') {
            $document->setValue('religion', 'Budha');
        }

        $document->setValue('job', $family_member->job);
        $address = $family_member->address;
        $split_address = str_split($address, 61);
        $address_1 = $split_address[0];
        if (count($split_address) > 1) {
            $address_2 = $split_address[1];
        } else {
            $address_2 = '';
        }
        $document->setValue('address_1', $address_1);
        $document->setValue('address_2', $address_2);
        $document->setValue('ktp_number', $family_member->nik);
        $document->setValue('kk_number', $family_member->family_card->family_card_number);

        $textPembuka = explode("\r\n", request('description'));
        $replacements_pembuka = [];
        foreach ($textPembuka as $text) {
            $replacements_pembuka[] = ['isi_ket' => $text];
        }

        $document->cloneBlock('pembuka_ket', count($replacements_pembuka), true, false, $replacements_pembuka);

        $name = request('title') . " " . ($cover_letter->id) . '.docx';
        $document->saveAs(storage_path('app/public/file/' . $name));
        $file = url('/') . '/storage/file/' . $name;

        $headers = array(
            'Content-Type: application/msword',
            'Content-Type: vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        $cover_letter->update([
            'title' => request('title') . " " . ($cover_letter->id),
            'file' => $file
        ]);

        $notification = 'Tidak ada fcm_token pada user';
        $users = User::where('id', auth('api')->user()->id)->pluck('fcm_token');

        $title_notif = 'Berhasil mengirim surat ';
        $body_notif = 'Surat anda telah kami terima, harap menunggu verifikasi';
        foreach ($users as $user) {
            if ($user != null) {
                $notification = $this->notify($title_notif, $body_notif)
                    ->data([
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'title' => $title_notif,
                        'body' => $body_notif
                    ])->to($user)->send();
            }
        }

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah surat pengantar'
        ]);

        DashboardNotification::create([
            'category' => "Surat pengantar",
            'description' => request('title') . ' | ' . $family_member->family_member_name,
        ]);

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengirim data surat';
        $data = ['cover_letter' => $cover_letter];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function downloadCoverLetter($id)
    {
        // $template = CoverLetter::findOrFail($id);
        // $url_domain = 'https://127.0.0.1:8000/';
        // $split_name = explode($url_domain . 'storage/file/', $template->file);
        // $file = public_path() . "/rt-pintar/storage/app/public/file/" . $split_name[1];

        // return response()->download($file, $template->title . ".docx");
        $template = CoverLetter::findOrFail($id);
        $file = file_get_contents($template->file);
        return response()->download($file, $template->title . ".docx");
    }

    public function edit($id)
    {
        $notice = CoverLetter::findOrFail($id);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'notice'), 200);
    }
    public function update(Request $request, CoverLetter $coverLetter)
    {
        $url_domain = '127.0.0.1:8000';

        if (request('status') == 'diterima') {
            $data = DataRt::first();
            $ttd = $data->sign_rt;
            $file_template = explode($url_domain . "/storage/file/", $coverLetter->file);
            $ttd_rt = explode($url_domain . "/storage/picture/", $ttd);
            $document = new TemplateProcessor(storage_path('app/public/file/' . $file_template[1]));
            $document->setImageValue('image', array('path' => storage_path('app/public/picture/' . $ttd_rt[1]), 'width' => 95, 'height' => 75, 'ratio' => false));

            $name = $coverLetter->title . '.docx';
            $document->saveAs(storage_path('app/public/file/' . $name));

            $headers = array(
                'Content-Type: application/msword',
                'Content-Type: vnd.openxmlformats-officedocument.wordprocessingml.document'
            );
            // $title_notif = 'Surat anda telah disetujui ';
            // $body_notif = 'Anda dapat mendownload di riwayat surat';
        }

        // if (request('status') == 'ditolak') {
        //     $title_notif = 'Surat anda telah ditolak ';
        //     $body_notif = 'Silahkan membuat surat baru';
        // }

        // $notification = 'Tidak ada fcm_token pada user';
        // $users = User::where('id', $coverLetter->family_member->user_id)->pluck('fcm_token');

        // foreach ($users as $user) {
        //     if ($user != null) {
        //         $notification = notify($title_notif, $body_notif)
        //             ->data([
        //                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        //                 'title' => $title_notif,
        //                 'body' => $body_notif
        //             ])->to($user)->send();
        //     }
        // }
        $coverLetter->update([
            'status' => request('status'),
            'description' => request('description')
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => "Ubah status surat"
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah status';
        return response()->json(compact('status', 'status_code', 'message'), 200);
    }
    public function destroy(CoverLetter $coverLetter)
    {
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Menghapus pengumuman'
        ]);
        $coverLetter->delete();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menghapus pengumuman';

        return response()->json(compact('status', 'status_code', 'message'), 200);
    }
    public function indexAdmin()
    {
        if (auth('api')->user()->admin == true || auth('api')->user()->pengurus == true) {
            $cover_letter = CoverLetter::where('status',  'menunggu')->with('family_member')->orderBy('id', 'asc')->get();
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mendapatkan data';
            $data = ['cover_letter' => $cover_letter];
            return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
        } else {
            $cover_letter = null;
            $status = 'error';
            $status_code = 400;
            $message = 'Anda bukan pengurus';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
    }
    public function updateStatusCoverLetter($id)
    {
        if (auth('api')->user()->admin == true || auth('api')->user()->pengurus == true) {
            $cover_letter = CoverLetter::findOrFail($id);

            $cover_letter->update([
                'status' => request('status')
            ]);
            $url_domain = '127.0.0.1:8000';
            if (request('status') == 'diterima') {
                $data = DataRt::first();
                $ttd = $data->sign_rt;
                $ttd_rt = explode($url_domain . "/storage/picture/", $ttd);
                $file_template = explode($url_domain . "/storage/file/", $cover_letter->file);
                $document = new TemplateProcessor(storage_path('app/public/file/' . $file_template[1]));
                $document->setImageValue('image', array('path' => storage_path('app/public/picture/' . $ttd_rt[1]), 'width' => 95, 'height' => 75, 'ratio' => false));
                $name = $cover_letter->title . '.docx';
                $document->saveAs(storage_path('app/public/file/' . $name));
                $headers = array(
                    'Content-Type: application/pdf',
                    'Content-Type: application/msword',
                    'Content-Type: vnd.openxmlformats-officedocument.wordprocessingml.document'
                );
                $title_notif = 'Surat anda telah disetujui ';
                $body_notif = 'Anda dapat mendownload di riwayat surat';
            }

            if (request('status') == 'ditolak') {
                $title_notif = 'Surat anda telah ditolak ';
                $body_notif = 'Silahkan membuat surat baru';
            }

            $notification = 'Tidak ada fcm_token pada user';
            $users = User::where('id', $cover_letter->family_member->user_id)->pluck('fcm_token');

            foreach ($users as $user) {
                if ($user != null) {
                    $notification = $this->notify($title_notif, $body_notif)
                        ->data([
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'title' => $title_notif,
                            'body' => $body_notif
                        ])->to($user)->send();
                }
            }

            ActivityHistory::create([
                'user_id' => auth('api')->user()->id,
                'description' => 'Pengurus | Mengubah status surat'
            ]);

            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mengubah status';
            return response()->json(compact('status', 'status_code', 'message'), 200);
        } else {
            $cover_letter = null;
            $status = 'error';
            $status_code = 400;
            $message = 'Anda bukan pengurus';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
    }
}
