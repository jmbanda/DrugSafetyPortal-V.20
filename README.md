## Intro
Originally a hack for [Tech Crunch Disrupt] Hackathon - San Francisco in 2015. [Drug Safety Portal v1.0] main idea was a consumer facing application, which allowed regular people to look what can be the potential issues of taking two drugs together. By combining APIs and research datasets, this application gave all possible evidence of drug interactions to users. Invited to further develop this app during the [2016 Biohackthon]. 

## Objectives during 2016 Biohackthon:
- Further standardization of resource
- Enable researchers to upload (and automatically standardize) their published DDI resources
- Provide facilities to download data
- Proper linkage with open linked data resources
- Allow people to Tweet to Drug Safety Portal
- Keep track of user queries for investigation of possibly new DDIs

## Pivoting Idea
Drug Safety Portal collects PUBLISHED drug-drug interactions….. Why not be a repository for unpublished ones …. Make it a full-fledged data repository!!

- Allow researchers to check if they are on the right path
- Keep all drug safety research in a centralized location
- Standardize all drug safety research before publication!

## Objective 1: Further standardization of resource - achieved!
- Before hackathon mapping between 6 sources, for 345 drugs 12 events.

- After hackathon mapping between 10 sources:
    - RxNorm to MeSH
    - RxNorm to OMOP Vocabulary
    - RxNorm to DrugBank
    - RxNorm to PharmGKB
    - MeSH to OMOP
    - MeSH to MeDRA
    - MeSH to SNOMED
    - SNOMED to ICD9
    - RxNorm to NDFRT
    - RxNorm to NDC
- For 1,123 drugs and 313 events

## Objective 2: Enable researchers to upload (and automatically standardize) their published *AND unpublished* DDI resources - achieved!

- Researchers can now:
    - Upload:
        - Single DDI’s
        - Full dataset of DDI’s
    - Submit pre-publication:
        - Single DDI’s and get a unique identifier
        - Full dataset of DDI’s and get a unique identifier
- Restrictions: Use one of 7 vocabularies for their drugs/events

## Objective 3: Provide facilities to download data  - achieved!
- Everything (even single items) can be downloaded in:
        
    - CSV
    - XML
    - PDF
    - Doc
    - RDF…. (75% complete)

## Objective 4: Proper linkage with open linked data resources - achieved!

- With the master back-end table linkage is possible to identifiers from:

    - Bio2RDF (UMLS, DrugBank, PharmGKB)
    - MeSH RDF
    - SNOMED (via bioportal)
    - RxNORM (via bioportal)

- TO-DO: Need to add this to the RDF output
- TO-DO: Need to add code for auto generation of drug safety portal RDF graph (using [LIDDI]’s schema)

## Objective 5: Allow people to Tweet to Drug Safety Portal - not yet :(

- Not a vital task…. But it is still onn he todo

## Objective 6: Keep track of user queries for investigation of possibly new DDIs 

- Implemented for all interaction with the consumer site
    - Bring on the click streams and query logs!!!

## Future work

- UX improvement!!!
- Cleanup (and publishing) of back-end code
- Look for journals that want to use the resource as a repository
- Add more published datasets:
    - Currently available: LIDDI, INDI, TWOSIDES, Shah Lab’s EHR predictions

## More info

- URL: [www.drugsafetyportal.org]
- Github: you are here :)
- Email: [jmbanda@stanford.edu]
- Twitter: [@drjmbanda]


[Tech Crunch Disrupt]: <https://techcrunch.com/event-type/disrupt/>
[Drug Safety Portal v1.0]: <https://github.com/jmbanda/DrugSafetyPortal>
[2016 Biohackthon]: <http://2016.biohackathon.org/>
[LIDDI]:<http://link.springer.com/chapter/10.1007%2F978-3-319-25010-6_18>
[@drjmbanda]: <https://twitter.com/drjmbanda>
[jmbanda@stanford.edu]: <mailto:jmbanda@stanford.edu>
[www.drugsafetyportal.org]: <http://www.drugsafetyportal.org>
