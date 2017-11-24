<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\style AS style;
    use \application\models\good AS good;
    use \application\models\file AS file;
    
    use \DateTime;    
    use \PDO;
    use \Imagick;
    use \ImagickPixel;
    use \CThumb;
    use \S3Thumb;
    use \Exception;
     
    class Controller_s3 extends \smashEngine\core\Controller
    {
        public function action_thumbs()
        {
            $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
            $Thumb->process(str_replace('s3/thumbs/', '', trim($this->page->url, '/')));
        }
        
        public function action_cache()
        {
            if (!empty($this->page->reqUrl[2]) && !empty($this->page->reqUrl[3]) && !empty($this->page->reqUrl[4]))
            {
                $g = explode('.', $this->page->reqUrl[4]);

                $tmpPath = UPLOADTODAY . md5(uniqid()) . '.jpeg';
                $path = implode('/', array('http://cache.maryjane.ru', $this->page->reqUrl[2], $this->page->reqUrl[3], $this->page->reqUrl[4]));

                try
                {
                    $good = new good($g[0]);
                    $S = new style($this->page->reqUrl[3]);
                    
                    switch ($this->page->reqUrl[2])
                    {
                        /**
                         * гаджеты
                         */
                        case 'phones':
                        case 'laptops':
                        case 'touchpads':
                        case 'ipodmp3':
                        case 'cases':
                        case 'moto':
                        case 'poster':
                        case 'posters':
                        case 'boards':
                        case 'bag':
                        case 'textile':
                        case 'stickers':
                        case 'pillows':
                                
                            // для гаджетов у которых нет основной картинки показываем уменьшенное превью исходника
                            if (empty($S->style_front_picture) && !empty($S->pics['rez']['path'])) {
                                
                                //$prv = $good->generateSrcPreview('phones', $path);
                                
                                if (!good::$srcs[$S->category]) {
                                    throw new Exception('Не существует такого исходника ' . $S->category, 11);
                                }
                                    
                                if (!$good->pics[$S->category]) {
                                    throw new Exception('Исходник ' . $S->category . ' у данной работы не загружен', 1);
                                }
                                
                                $i = new Imagick();
                                $i->newImage(500, 512, new ImagickPixel('white'));
                                
                                $src = new Imagick(ROOTDIR . $good->pics[$S->category]['path']);
                                $src->scaleImage($src->getImageWidth() * ($i->getImageHeight() / $src->getImageHeight()), $i->getImageHeight());
                                
                                $i->compositeImage($src, Imagick::COMPOSITE_OVER, $i->getImageWidth() / 2 - $src->getImageWidth() / 2, $i->getImageHeight() / 2 - $src->getImageHeight() / 2);
                                
                                $i->setImageFormat('jpeg');
                                
                                createDir(dirname($tmpPath));
                                
                                $i->writeImage(ROOTDIR . $tmpPath);
                                
                                $prv['path'] = file::move2S3($tmpPath, $newpath);
                                
                            } else {
                                
                                $prv = $good->generateGadgetPreview($S->id, 500, 512, in_array($S->category, ['phones', 'laptops', 'touchpads', 'ipodmp3', 'cases']) ? 'both' : styleCategory::$BASECATS[$S->category]['def_side'], $tmpPath, false, '', $path);
                                
                            }
                            
                            
                        break;
                            
                        case 'patterns':
                        case 'patterns-sweatshirts':
                        case 'patterns-tolstovki':
                        case 'patterns-bag':
                        case 'bomber':
                        case 'body':
                            
                            $side = 'front';
                
                            if ($g[1] == 'model_back')
                                $side = 'back';
                            
                            $prv = $good->generateGadgetPreview($S->id, 500, 512, $side, $tmpPath, false, '', $path);
                            
                            break;
                            
                        case 'cup':
                            
                            if (!in_array($g[2], array('front', 'side', 'lside'))) {
                                $g[2] = styleCategory::$BASECATS['cup']['def_side'];
                            }
                
                            $prv = $good->generateCupPreview($S->id, 500, 512, $g[2], $path);
                            
                            break;
                            
                        /**
                         * авто
                         */
                        case 'auto':
                            
                            foreach ($good->pics['as_oncar'] as $k => $v) {
                                if ($v['id'] > 0) {
                                    $prv['path'] = \application\models\file::move2S3($v['path'], str_replace('http://cache.maryjane.ru', '', $path), 'cache.maryjane.ru', 0);
                                    break;
                                }
                            }
                            
                            break;
                        
                        /**
                         * авто
                         */
                        case 'postcards':
                        case 'stickerset':
                            
                            $i = new Imagick();
                            $i->newImage(500, 512, new ImagickPixel('white'));
                            
                            $src = new Imagick(ROOTDIR . $good->pics['stickerset_zoom']['path']);
                            $src->scaleImage($src->getImageWidth() * ($i->getImageHeight() / $src->getImageHeight()), $i->getImageHeight());
                            
                            $i->compositeImage($src, Imagick::COMPOSITE_OVER, $i->getImageWidth() / 2 - $src->getImageWidth() / 2, $i->getImageHeight() / 2 - $src->getImageHeight() / 2);
                            
                            $i->setImageFormat('jpeg');
                            
                            createDir(dirname($tmpPath));
                            
                            $i->writeImage(ROOTDIR . $tmpPath);
                            
                            $prv['path'] = file::move2S3($tmpPath, str_replace('http://cache.maryjane.ru', '', $path), 'cache.maryjane.ru');
                                
                          break;
                          
                        /**
                         * превью на тряпках
                         */
                        default:
                
                            $side = 'front';
                
                            if ($g[1] == 'model') {
                                $side .= '_model';
                            }

                            if ($g[1] == 'back_model' || $g[1] == 'model_back') { 
                                $side = 'back_model';
                            }
                                
                            $prv = $good->generatePreview($S->id, 500, 512, $side, $tmpPath, false, $path);
                        
                            break;
                    }

                    // превью лежит на нашем сервере
                    if (!empty($prvlocal))
                    {
                        $i = createImageFrom(ROOTDIR . $prvlocal);
                        header('Content-type: image/jpeg');
                        imagejpeg($i, NULL, 98);
                    }
                    
                    // превью в облаке
                    if (!empty($prv['path']))
                    {
                        $thumb = CThumb::create($prv['path']);
                        $thumb->open()->show();
                    }
                    
                    exit();
                }
                catch (Exception $e)
                {
                    printr($e->getMessage());
                }
            }

            exit();
        }
    }
        