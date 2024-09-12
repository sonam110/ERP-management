<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLeadsFollowUp extends Model
{
     protected $fillable = [
        'lead_id',
        'title',
        'description',
        'date',
        'time',
        'pipeline_id',
        'sources',
        'stage_id',
        'status',
    ];

    public function pipeline()
    {
        return $this->hasOne('App\Models\Pipeline', 'id', 'pipeline_id');
    }
    public function stage()
    {
        return $this->hasOne('App\Models\LeadStage', 'id', 'stage_id');
    }
    public static function getFollowUpSummary($followups)
    {
        $total = 0;

        foreach($followups as $follow)
        {
            $total ++;
        }

        return $total;
    }
}
