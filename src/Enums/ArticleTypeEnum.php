<?php


enum ArticleTypeEnum: string
{
    case TYPE_ARTICLE = 'article';
    case TYPE_URL = 'url';
    case TYPE_URL_OUT = 'url_out';
    case TYPE_ATTACHMENT = 'attachment';

//    case TYPE_ACTIVITY = 'activity';


    public static function options(): array
    {
        return [
            self::TYPE_ARTICLE->value => '文章',
            self::TYPE_URL->value => '内部链接',
            self::TYPE_URL_OUT->value => '外部链接',
            self::TYPE_ATTACHMENT->value => '附件',
//            self::TYPE_ACTIVITY->value => '活动',
        ];
    }

    public function text(): string
    {
        return match ($this) {
            self::TYPE_ARTICLE => '文章',
            self::TYPE_URL => '内部链接',
            self::TYPE_ATTACHMENT => '附件',
            self::TYPE_URL_OUT => '外部链接',
//            self::TYPE_ACTIVITY => '活动',
        };
    }
}
