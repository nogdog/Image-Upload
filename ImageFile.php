<?php
/**
 * @author NogDog
 */

/**
 * Class ImageFile
 * Processing of image file uploads
 */
class ImageFile
{
    private $imageDir;
    private $tableName;
    private $db;
    private $imageType;
    private $data = array();
    public $lastError;

    private $allowedImageTypes = array(
        IMG_PNG,
        IMG_JPEG,
        IMG_GIF
    );

    /**
     * Constructor
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Process a newly uploaded file and insert info into DB
     *
     * @param array $file e.g.: $_FILES['form_name']
     * @return int
     */
    public function create(array $file)
    {
        if($this->validFile($file)) {
            if(!move_uploaded_file($file['tmp_name'], $this->imageDir.'/'.$file['name'])) {
                $this->lastError = "unable to move tmp file";
                return false;
            }
            $stmt = $this->db->prepare("
                INSERT INTO {$this->tableName} (file_name, directory, image_type, upload_timestamp)
                VALUES(':file_name', ':directory', ':image_type', NOW())
            ");
            $stmt->execute(array(
                ':file_name' => $file['name'],
                ':directory' => $this->imageDir,
                ':image_type' => $this->imageType
            ));
            $this->data = $file;
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * @param int $id if not supplied, return current data if any
     * @return array
     * @todo    get data from DB
     */
    public function read($id = null)
    {
        if(!empty($id))
        {
            // use $id to read data from DB and store in $this->data
        }
        return $this->data;
    }

    public function update($id, array $data)
    {
        // TBD
    }

    public function delete($id)
    {
        // TBD
    }

    /**
     * Validate an array for one file from $_FILES
     * @param array $file
     * @return bool
     */
    public function validFile(array $file)
    {
        if(!empty($file['error']))
        {
            error_log($file['error']);
            return false;
        }
        if(!is_readable($file['tmp_name']))
        {
            error_log("Could not find/read '{$file['tmpName']}");
            return false;
        }
        if(($info = getimagesize(($file['tmp_name']))) == false)
        {
            $this->lastError = "Not an image file?";
            return false;
        }
        if(!in_array($info, $this->allowedImageTypes)) {
            $this->lastError = "Invalid image type ".$info[2];
            return false;
        }
        $this->imageType = $info[2];
        return true;
    }
}
