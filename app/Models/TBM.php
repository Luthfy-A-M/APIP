namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBM extends Model
{
    use HasFactory;

    protected $fillable = [
        'dept_code',
         'section',
          'shift',
           'date' => 'datetime',
            'time' ,
             'title' ,
             'pot_danger_point',
             'most_danger_point',
             'key_word',
             'prepared_by',
             'prepared_by_sign_date',
             'checked_by',
             'checked_by_sign_date',
             'reviewed_by',
             'reviewed_by_sign_date',
             'approved1_by',
             'approved1_by_sign_date',
             'approved2_by',
             'approved2_by_sign_date',
             'status'
    ];

}
