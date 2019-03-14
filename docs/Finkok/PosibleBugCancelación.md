# Disponibilidad de CFDI para cancelar

Un CFDI recién timbrado no se encuentra disponible para su cancelación de forma inmediata,
hay que esperar de 0:15 hasta 1:30 para poder hacer la cancelación.

# Estados de cancelación

Según la documentación del [método cancel](https://wiki.finkok.com/doku.php?id=cancel_method),
con el nuevo esquema de cancelación, se podrían obtener estas respuestas correctas:

- SOAP Response con EstatusUUID 201 de un CFDI cancelado sin aceptación
  EstatusUUID: 201, EstatusCancelacion: Cancelado sin aceptación
  Entiendo que fue cancelado sin necesidad de validación por el receptor

- SOAP Response con EstatusUUID 202 de un comprobante que fue cancelado sin aceptación
  EstatusUUID: 202, EstatusCancelacion: Cancelado sin aceptación
  Entiendo que fue cancelado sin necesidad de validación por el receptor

- SOAP Response con EstatusUUID 201 de un comprobante que se encuentra en proceso
  EstatusUUID: 201, EstatusCancelacion: En proceso
  Entiendo que se está en espera de cancelación porque aún no ocurre la validación por el receptor

Sin embargo, en entorno de pruebas, si se realiza una doble cancelación sin necesidad de aceptación,
en el primer intento devuelve 201 - Cancelado sin aceptación
pero en el segundo intento devuelve 201 - En proceso

En el primer intento lo da por cancelado, en el segundo lo da por sometido a aceptación por parte del receptor.
La respuesta esperada es en realidad 202 - Cancelado sin aceptación


# Estrategias de cancelación de Finkok

- `PublishedSharedPrivateKey`: Llave compartida almacenada en el portal.
Generar una llave privada con el password de Finkok y subirla al portal.
Las solicitudes irán sin certificado ni llave. No se almacena password en la configuración.

- `SendSharedPrivateKey`: Llave compartida almacenada localmente.
Generar una llave privada con el password de Finkok y alma subirla al portal.
Las solicitudes irán con certificado y llave. No se almacena password de la llave.

- `ConvertToSharedPrivateKey`: Llave compartida generada al momento a partir de otra llave.
Las solicitudes irán con certificado y llave. Se almacena el password de la llave.

- `SignCancelRequest`: Firmar localmente la solicitud y no compartir la llave.
Las solicitudes irán con la solicitud firmada localmente. 
Las solicitudes irán sin certificado ni llave. Se almacena el password de la llave.

### Pasos para generar la firma para Finkok

Estudiar:
    - [https://github.com/marcelxyz/php-XmlDigitalSignature]()
    - [Generating a Signature](https://www.phpacademy.xyz/xml_webservices/generating_a_signature.html)
    - [How to create a SAT Cancelacion document using CryptoSys PKI Pro](https://www.cryptosys.net/pki/satcancelcfd.html)

Crear un nodo de cancelación que contiene:
```
<Cancelacion
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema"
    xmlns="http://cancelacfd.sat.gob.mx"
    RfcEmisor="" Fecha="yyyy-MM-ddTHH:mm:ss"
    >
    <Folios>
        <UUID>...</UUID>
    </Folios>
</Cancelacion>
```

Obtener el IssuerName y IssuerName del certificado

Crear la firma Signature
    - SignedInfo
        - CanonicalizationMethod REC-xml-c14n-20010315
        - SignatureMethod rsa-sha1
        - Reference ""
            - Transforms / Transform enveloped-signature
            - DigestMethod sha1
            - DigestValue (some content)
    - SignatureValue (some content)
    - KeyInfo
        - X509Data
            - X509IssuerSerial
                - X509IssuerName
                - X509SerialNumber
            - X509Certificate (some content)
        - KeyValue
            - RSAKeyValue
                - Modulus
                - Exponent
                 
Agregarla al documento como hijo de Cancelación (root) 
