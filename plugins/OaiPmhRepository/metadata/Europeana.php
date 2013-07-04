<?php
/** Metadata generator for creating ESE OAI outputs
*
* Class implmenting metadata output for the required ESE metadata format.
* Also uses grid reference stripping and redisplay tools. This builds on the
* OAI classes generated for the Omeka system.
*
*  @package OaiPmhRepository
* @subpackage Metadata
* @author Daniel Pett
* @version 1
* @since 22 September 2011
*/
class OaiPmhRepository_Metadata_Europeana
extends OaiPmhRepository_Metadata_Abstract {

/** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'ese';

    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.europeana.eu/schemas/ese/';

    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.europeana.eu/schemas/ese/ESE-V3.4.xsd';

    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';

    const DC_METADATA_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';

    const DC_TERMS_NAMESPACE	= 'http://purl.org/dc/terms/';

/** Add meta data to the xml response in this ESE
*/

    public function appendMetadata($metadataElement) {
        $europeana = $this->document->createElementNS(
            self::METADATA_NAMESPACE, 'ese:record');
        $metadataElement->appendChild($europeana);    
        
        $metadataElement = $this->document->createElement('metadata');
    
        $europeana->setAttribute('xmlns:dc',self:: DC_NAMESPACE_URI);
        $europeana->setAttribute('xmlns:oai_dc',self::DC_METADATA_NAMESPACE);
        $europeana->setAttribute('xmlns:ese', self::DC_NAMESPACE_URI);
        $europeana->setAttribute('xmlns:xsi', parent::XML_SCHEMA_NAMESPACE_URI);
        $europeana->setAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE . ' ' . self::METADATA_SCHEMA);
        
        $dcElementNames = array( 'title', 'creator', 'subject', 'description',
                                 'publisher', 'contributor', 'date', 'type',
                                 'format', 'identifier', 'source', 'language',
                                 'relation', 'coverage', 'rights' );

        foreach($dcElementNames as $elementName){
            $upperName = Inflector::camelize($elementName);
            $dcElements = $this->item->getElementTextsByElementNameAndSetName(
                $upperName, 'Dublin Core');
            foreach($dcElements as $elementText)
            {
                $this->appendNewElement($europeana, 'dc:'.$elementName, $elementText->text);
            }
        }

        $ese = array();
        $ese['object'] = digitool_get_thumb_url($this->item);
        $ese['provider'] = 'Flandrica.be';
        $ese['type'] = 'IMAGE';
        $ese['rights'] = 'http://creativecommons.org/publicdomain/zero/1.0/';
        $Collection = get_collection_for_item($this->item);
        $ese['dataProvider'] = $Collection->name;        
        $ese['isShownBy'] = digitool_get_thumb_url($this->item);
        $ese['isShownAt'] = WEB_ROOT.item_uri('show',$this->item);

        foreach($ese as $k => $v) {
            $this->appendNewElement($europeana, 'ese:'.$k, $v);
        }
    }

    /**
    * Returns the OAI-PMH metadata prefix for the output format.
    *
    * @return string Metadata prefix
    */
    public function getMetadataPrefix() {
        return self::METADATA_PREFIX;
    }

    /**
    * Returns the XML schema for the output format.
    *
    * @return string XML schema URI
    */
    public function getMetadataSchema() {
        return self::METADATA_SCHEMA;
    }

    /**
    * Returns the XML namespace for the output format.
    *
    * @return string XML namespace URI
    */
    public function getMetadataNamespace() {
        return self::METADATA_NAMESPACE;
    }


}