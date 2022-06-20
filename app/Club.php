<?php

namespace App;

use Jikan\Request\Club\ClubRequest;

class Club extends JikanApiSearchableModel
{
    protected array $filters = ["order_by", "sort"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mal_id', 'url', 'images', 'title', 'members_count', 'pictures_count', 'category', 'created', 'type', 'staff', 'anime_relations', 'manga_relations', 'character_relations'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['images'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clubs';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '_id', 'request_hash', 'expiresAt', 'images'
    ];

    public static function scrape(int $id)
    {
        $data = app('JikanParser')->getClub(new ClubRequest($id));

        return json_decode(
            app('SerializerV4')
                ->serialize($data, 'json'),
            true
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (string) $this->mal_id,
            'mal_id' => (string) $this->mal_id,
            'title' => $this->title,
            'category' => $this->category,
            'created' => $this->convertToTimestamp($this->created),
            'type' => $this->type
        ];
    }

    public function typesenseQueryBy(): array
    {
        return ['title'];
    }
}
