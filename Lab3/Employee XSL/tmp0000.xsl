<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions" xmlns:math="http://www.w3.org/2005/xpath-functions/math" xmlns:array="http://www.w3.org/2005/xpath-functions/array" xmlns:map="http://www.w3.org/2005/xpath-functions/map" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:err="http://www.w3.org/2005/xqt-errors" exclude-result-prefixes="array fn map math xhtml xs err" version="3.0">
	<xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/" name="xsl:initial-template">
	 <html>
      <head>
      </head>
      <body>
        <h2>Employee Information</h2>
        <table border="1">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Home Phone</th>
            <th>Work Phone</th>
            <th>Mobile Phone</th>
            <th>Address</th>
            <th>Region</th>
            <th>City</th>
            <th>Country</th>
          </tr>
          <xsl:for-each select="employees/employee">
            <tr>
              <td><xsl:value-of select="name" /></td>
              <td><xsl:value-of select="email" /></td>
              <td><xsl:value-of select="phones/phone[@type='home']" /></td>
              <td><xsl:value-of select="phones/phone[@type='work']" /></td>
              <td><xsl:value-of select="phones/phone[@type='mobile']" /></td>
              <td><xsl:value-of select="addresses/address/street" /></td>
              <td><xsl:value-of select="addresses/address/Region" /></td>
              <td><xsl:value-of select="addresses/address/city" /></td>
              <td><xsl:value-of select="addresses/address/country" /></td>
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
	</xsl:template>
</xsl:stylesheet>
